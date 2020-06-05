<?php

namespace App\Http\Controllers\Discussions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discussions\StoreDiscussionRequest;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store');
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
        // Make the slug
        $slug = Str::slug($request->title);
        
        // Check if the slug exists
        if (Discussion::where('slug', $slug)->count() > 0) {
            // if yes, therefore we gonna generate another slug :)
            $slug = $slug . "-" . Str::random(11);

            // Maybe we need another check or a while loop 
            // But a random string with 11 chars (no need i think ;) )
        }

        // Create discussion
        $discussion = new Discussion;
        $discussion->title = $request->title;
        $discussion->slug = $slug;
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
}
