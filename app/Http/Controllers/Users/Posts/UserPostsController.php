<?php

namespace App\Http\Controllers\Users\Posts;

use App\Http\Controllers\Controller;
use App\Http\Resources\Posts\PostResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserPostsController extends Controller
{
	public function __construct()
	{
        // \Illuminate\Support\Facades\Auth::setUser(\App\Models\User::find(2));
		// $this->middleware('auth:sanctum');
	}
	
    public function index(User $user, Request $request)
    {
    	$posts = $user->posts()->with(['lessVotes'])->withCount('votes')->latest()->limit(5)->get();
        $posts->each->setRelation('user', $user);
    	$user->setRelation('posts', $posts);

    	return PostResource::collection($posts);
    }
}
