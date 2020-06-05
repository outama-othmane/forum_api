<?php

namespace Tests\Feature\Discussions;

use App\Models\Channel;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DiscussionScopingTest extends TestCase
{
    public function test_it_can_scope_by_channel()
    {
        $channel = factory(Channel::class)->create();

        $discussion = factory(Discussion::class)->create([
            'channel_id' => $channel->id,
        ]);

        $anotherDiscussion = factory(Discussion::class)->create();

        $this->json('get', '/api/discussions?channel=' . $channel->slug)
            ->assertJsonCount(1, 'data');
    }

    public function test_it_can_scope_order_by_channel()
    {
        $channel = factory(Channel::class)->create(['name' => 'Abc']);
        $channel2 = factory(Channel::class)->create(['name' => 'Zwy']);

        $discussion = factory(Discussion::class)->create([
            'channel_id' => $channel2->id,
        ]);

        $anotherDiscussion = factory(Discussion::class)->create([
            'channel_id' => $channel->id
        ]);

        $this->json('get', '/api/discussions?channels=desc&perPage=1')
            ->assertJsonFragment([
                'title' => $discussion->title,
            ]);
    }

    public function test_it_can_scope_order_by_last_post_date()
    {
        $discussion = factory(Discussion::class)->create();
        $discussion2 = factory(Discussion::class)->create();
        

        factory(Post::class)->create([
            'discussion_id' => $discussion->id,
            'created_at' => Carbon::now(),
        ]);

       $post =  factory(Post::class)->create([
            'discussion_id' => $discussion->id,
            'created_at' => Carbon::now()->addYear(),
        ]);

        factory(Post::class)->create([
            'discussion_id' => $discussion2->id,
            'created_at' => Carbon::now(),
        ]);

        $this->json('get', '/api/discussions?last_post_date=desc&perPage=1')
            ->assertJsonFragment([
                'id' => $discussion->id,
                'content' => $post->content,
            ]);
    }


    public function test_it_can_scope_order_by_activity()
    {
        $discussion = factory(Discussion::class)->create();
        $discussion2 = factory(Discussion::class)->create();

        factory(Post::class, 5)->create([
            'discussion_id' => $discussion->id,
        ]);

        factory(Post::class, 2)->create([
            'discussion_id' => $discussion2->id,
        ]);

        $this->json('get', '/api/discussions?activity=desc&perPage=1')
            ->assertJsonFragment([
                'id' => $discussion->id,
            ]);
    }

    public function test_it_can_scope_by_closed_at_column_true()
    {
        $discussion = factory(Discussion::class)->create(['closed_at' => Carbon::now()]);
        $anotherDiscussion = factory(Discussion::class)->create();
        
        $this->json('get', '/api/discussions?closed=true')
            ->assertJsonFragment([
                'id' => $discussion->id,
            ]);
    }

    public function test_it_can_scope_by_closed_at_column_false()
    {
        $discussion = factory(Discussion::class)->create(['closed_at' => Carbon::now()]);
        $anotherDiscussion = factory(Discussion::class)->create();
        
        $this->json('get', '/api/discussions?closed=false')
            ->assertJsonFragment([
                'id' => $anotherDiscussion->id,
            ]);
    }

    public function test_it_filters_by_no_posts_yet()
    {
        $discussion = factory(Discussion::class)->create();
        $posts = factory(Post::class, 3)->create(['discussion_id' => $discussion->id]); 

        $anotherDiscussion = factory(Discussion::class)->create();
        $posts = factory(Post::class)->create(['discussion_id' => $anotherDiscussion->id]); 
        
        $this->json('get', '/api/discussions?no_posts=true')
            ->assertJsonCount(1, "data");
    }
}
