<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\ShowUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function __construct()
	{
        // \Illuminate\Support\Facades\Auth::setUser(\App\Models\User::find(2));
		// $this->middleware('auth:sanctum');
	}

    public function show(User $user, Request $request)
    {
    	$posts_count = $user->posts()->count();
    	$discussions_count = $user->discussions()->count();

    	$user->posts_count = $posts_count;
    	$user->discussions_count = $discussions_count;
    	
    	return new ShowUserResource($user);
    }
}
