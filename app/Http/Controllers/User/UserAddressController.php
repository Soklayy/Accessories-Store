<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAdressRequest;

class UserAddressController extends Controller
{
    public function update(UserAdressRequest $request){
        $userAddress = Auth()->user()->address;
        $request->validated();
        $userAddress->update($request->all());
        return $this->sendMesssage('Updated your address');
    }
}
