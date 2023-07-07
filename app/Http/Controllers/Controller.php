<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendReponse($data = null,string $message="",$code = 200)
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ],$code);
    }

    public function sendMesssage(string $message="",$code = 200)
    {
        return response()->json([
            'message' => $message,
        ],$code);
    }
}
