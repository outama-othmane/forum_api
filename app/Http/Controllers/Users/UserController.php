<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:sanctum');
	}

    public function show(User $user, Request $request)
    {
    	// $posts = $user->posts()->paginate(10);
    	// $user->setRelation('posts', $posts);

    	return new UserResource($user);
    }
}
