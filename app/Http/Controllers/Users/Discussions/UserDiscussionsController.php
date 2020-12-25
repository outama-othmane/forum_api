<?php

namespace App\Http\Controllers\Users\Discussions;

use App\Http\Controllers\Controller;
use App\Http\Resources\Discussions\DiscussionResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserDiscussionsController extends Controller
{
	public function __construct()
	{
		// $this->middleware('auth:sanctum');
	}
	
    public function index(User $user, Request $request)
    {
    	$discussions = $user->discussions()->with(['channel', 'lastPost' => function($query) {
                return $query->with(['user', 'currentUserVotes']);
            }])->latest()->limit(5)->get();

    	return DiscussionResource::collection($discussions);
    }
}
