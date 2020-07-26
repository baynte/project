<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login( Request $req ) {
        $credVal = $req->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        
        if( !Auth::attempt($credVal) ){
            return response( ['message'=> 'invalid credentials.'], 401 );
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        
        return response([ 'user'=> Auth::user(), 'access_token' => $accessToken ]);
    }

}
