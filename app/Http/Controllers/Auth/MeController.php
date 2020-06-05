<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\PrivateUserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:sanctum');
    }

    public function me(Request $request)
    {
    	return new PrivateUserResource($request->user());
    }
}
