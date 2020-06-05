<?php

namespace App\Http\Controllers\Posts\Votes;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;

class PostVotesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:sanctum');
	}

    public function store(Post $post, Request $request)
    {
        if ($this->checkVoteExistence($post, $request)) {
            return;
        }

        $vote = $post->votes()->make([]);
        $request->user()->votes()->save($vote);
    }

    public function destroy(Post $post, Request $request)
    {
        Vote::where([
            'post_id' => $post->id, 
            'user_id' => $request->user()->id
        ])->delete();
    }

    protected function checkVoteExistence(Post $post, Request $request)
    {
        return Vote::where([
            'post_id' => $post->id, 
            'user_id' => $request->user()->id
        ])->count() > 0;
    }

    // protected function addVote(Post $post, Request $request)
    // {
    //     $vote = Vote::where([
    //         'post_id' => $post->id, 
    //         'user_id' => $requser->user()->id
    //     ])->count();
        
    //     if ($vote > 0) {
    //         return;
    //     }

    //     $vote = $post->votes()->make([]);
    //     $request->user()->votes()->save($vote);
    // }

    // protected function deleteVote(Post $post, Request $request)
    // {
    //     Vote::where([
    //         'post_id' => $post->id, 
    //         'user_id' => $requser->user()->id
    //     ])->delete();

    //     return;
    // }
}
