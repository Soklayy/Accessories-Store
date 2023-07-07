<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Shopping;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
    
            Shopping::create([
                'user_id'=>$user->id
            ]);
    
            UserAddress::create([
                'user_id'=>$user->id
            ]);
            $token = $user->createToken('access_token')->plainTextToken;
            DB::commit();
            return $this->sendReponse([
                'token' => $token,
            ],'create account success');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }



    // login api
    public function login(Request $request)
    {
 
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        $matchPassword = Hash::check($request->password, $user->password);

        if (!$matchPassword) {
            return $this->sendMesssage("password incorrect");
        }

        return $this->sendResponse([
            'token' => $user->createToken('my-token')->plainTextToken,
        ],'Login success');


    }
 

    // logout api
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return $this->sendMesssage("logout success");
    }


    //me
    public function me()
    {
        return $this->sendResponse([
            'id' => Auth()->user()->id,
            'name' => Auth()->user()->name,
            'email' => Auth()->user()->email,
        ],'Your personal data');
    }
}
