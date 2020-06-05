<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:sanctum');
    }

    public function logout(Request $request)
    {
    	$currentToken = $request->user()->currentAccessToken()->id;
    	$request->user()->tokens()->where('id', $currentToken)->delete();

    	return;
    }
}
