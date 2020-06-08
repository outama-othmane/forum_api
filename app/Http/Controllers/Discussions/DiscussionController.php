<?php

namespace App\Http\Controllers\Discussions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussions\StoreDiscussionRequest;
use App\Http\Requests\Discussions\UpdateDiscussionRequest;
use App\Http\Resources\Discussions\DiscussionResource;
use App\Http\Resources\Discussions\ShowDiscussionResource;
use App\Models\Channel;
use App\Models\Discussion;
use App\Scoping\Scopes\ChannelScope;
use App\Scoping\Scopes\ClosedScope;
use App\Scoping\Scopes\Discussions\NoPostsYetScope;
use App\Scoping\Scopes\Discussions\Ordering\ActivityOrder;
use App\Scoping\Scopes\Discussions\Ordering\ChannelsOrder;
use App\Scoping\Scopes\Discussions\Ordering\LastPostDateOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['show', 'index']);
    }

    public function index(Request $request)
    {	
        $discussions = Discussion::with(['channel', 'lastPost' => function($query) {
                return $query->with(['user', 'lessVotes']);
            }])
            ->withScopes($this->scopes())
        	->latest()
        	->paginate(
                min($request->get('perPage', 10), 10)
            );

        return DiscussionResource::collection($discussions);
    }

    public function show(Discussion $discussion, Request $request)
    {
        // ['lastPost.user']
    	$discussion->load(['channel']);
        
        return (new ShowDiscussionResource($discussion));
    }

    public function store(StoreDiscussionRequest $request)
    {
        // Create discussion
        $discussion = new Discussion;
        $discussion->title = $request->title;
        $discussion->slug = $this->generateSlug($request);
        $discussion->channel_id = $request->channel_id;
        $discussion->started_user_id = $request->user()->id;
        $discussion->save();

        // Check if the discussion stored
        if (! $discussion ) {
            return $this->discussionNotStored();
        }

        // Create post
        $post = $this->createFirstPost($discussion, $request);

        // update
        $discussion->started_post_id = $post->id;
        $discussion->save();

        return (new ShowDiscussionResource($discussion));
    }

    public function update(Discussion $discussion, UpdateDiscussionRequest $request)
    {
        // Check if the current user can delete the discussion
        $this->authorize('update', $discussion);

        if ($discussion->title == $request->title && intval($request->close) != 1) {
            return ['d'];
        }

        if ($discussion->title != $request->title) {
            $discussion->title = $request->title;
            $discussion->slug = $this->generateSlug($request, $discussion->id);
        }

        if (intval($request->close) == 1 && is_null($discussion->closed_at)) {
            $discussion->closed_at = Carbon::now();
        }
        
        $discussion->save();
        return ['dd'];
    }

    public function destroy(Discussion $discussion, Request $request)
    {
        // Check if the current user can delete the discussion
        $this->authorize('delete', $discussion);

        $discussion->delete();
    }

    protected function scopes()
    {
        return [
            'channel'           => new ChannelScope(),
            'channels'          => new ChannelsOrder(),
            'last_post_date'    => new LastPostDateOrder(),
            'activity'          => new ActivityOrder(),
            'closed'            => new ClosedScope(),
            'no_posts'          => new NoPostsYetScope(),
        ];
    }

    protected function discussionNotStored()
    {
        return response()->json([
            'message' => Lang::get('discussion.not_stored')
        ], 500);
    }

    protected function createFirstPost(Discussion $discussion,StoreDiscussionRequest $request)
    {
        // Make post
        $post = $discussion->posts()->make([
            'content' => $request->content,
            'ip_addr' => $request->ip(),
        ]);

        // Save the post :)
        return $request->user()->posts()->save($post);
    }

    protected function generateSlug(Request $request, int $ignore = 0)
    {
        // Make the slug
        $slug = Str::slug($request->title);
        
        $discussion = Discussion::where('slug', $slug);

        if ($ignore > 0) {
            $discussion->whereKeyNot($ignore);
        }

        // Check if the slug exists
        if ($discussion->count() > 0) {
            // if yes, therefore we gonna generate another slug :)
            $slug = $slug . "-" . Str::random(11);

            // Maybe we need another check or a while loop 
            // But a random string with 11 chars (no need i think ;) )
        }
        return $slug;
    }
}
