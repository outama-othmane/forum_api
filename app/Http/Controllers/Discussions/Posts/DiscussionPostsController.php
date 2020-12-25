<?php

namespace App\Http\Controllers\Discussions\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Resources\Posts\PostResource;
use App\Models\Discussion;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class DiscussionPostsController extends Controller
{
	public function __construct()
	{
        $this->middleware('auth:sanctum')->except(['index']);
	}

    public function index(Discussion $discussion, Request $request)
    {
        // Get all the posts
    	$posts = $discussion->posts()
            ->with(['user', 'currentUserVotes'])
            ->withCount('votes')
            ->paginate(
                min($request->get('perPage', 10), 10)
            );

        // Set discussion relation to each post
        // Check if the posts var is an instance of LengthAwarePaginator
        if ($posts instanceof LengthAwarePaginator) {
            $posts->getCollection()->each->setRelation('discussion', $discussion);
        } elseif ($posts instanceof Collection) {
            $posts->each->setRelation('discussion', $discussion);
        }

        return PostResource::collection($posts);
    }

    public function store(Discussion $discussion, StorePostRequest $request)
    {
        if ($discussion->isClosed === true) {
            $this->discussionClosedResponse();
        }

        // Check if the parent_id exists and belongs to the discussion
        if ( ! is_null($request->parent_id) ) {
            $this->checkParentPost($discussion, $request);
        }

        // Make and save the post :)
        $post = $this->createPost($discussion, $request);

        // Show the results
        $post->setRelation('user', $request->user());
        return (new PostResource($post));
    }

    public function update(Discussion $discussion, Post $post, UpdatePostRequest $request)
    {
        // Check if the post belongs to discussion
        // if (!$this->checkPostBelongsToDiscussion($discussion, $post)) {
        //     return abort(403);
        // }

        // Check if the current user is the owner of the post
        $this->authorize('update', $post);

        // Check if the discussion is closed
        if ($discussion->isClosed === true) {
            $this->discussionClosedResponse();
        }


        if ($post->content === $request->content) {
            return;
        }

        $post->content = $request->content;
        $post->save();
    }

    public function destroy(Discussion $discussion, Post $post, Request $request)
    {
        // Check if the post belongs to discussion
        // if (!$this->checkPostBelongsToDiscussion($discussion, $post)) {
        //     return abort(403);
        // }

        $post->setRelation('discussion', $discussion);

        // Check if the current user is the owner of the post
        $this->authorize('delete', $post);

        // Check if the discussion is closed
        if ($discussion->isClosed === true) {
            $this->discussionClosedResponse();
        }

        $post->delete();
    }

    protected function checkParentPost(Discussion $discussion, Request $request)
    {
        $post = Post::find($request->parent_id);

        if ($post && $post->discussion_id === $discussion->id) {
            // Maybe check if the $post has a parent
            // If true, change parent_id to the $post->parent_id
            // XD
            return;
        }

        throw ValidationException::withMessages([
            'parent_id' => [Lang::get('validation.exists', ['attribute' => 'parent_id'])],
        ]);
    }

    protected function discussionClosedResponse()
    {
        throw ValidationException::withMessages([
            'content' => [Lang::get('discussion.is_closed')],
        ]);   
    }

    protected function createPost(Discussion $discussion,Request $request)
    {
        $post = $discussion->posts()->make([]);
        $post->content = $request->content;
        $post->ip_addr = $request->ip();
        $post->parent_id = $request->parent_id;
        $post->user_id = $request->user()->id;
        $post->save();

        return $post;
    }

    protected function checkPostBelongsToDiscussion(Discussion $discussion, Post $post)
    {
        return $discussion->id === $post->discussion_id;
    }
}
