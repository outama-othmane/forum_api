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
        $channel = Channel::factory()->create();

        $discussion = Discussion::factory()->create([
            'channel_id' => $channel->id,
        ]);

        $anotherDiscussion = Discussion::factory()->create();

        $this->json('get', '/api/discussions?channel=' . $channel->slug)
            ->assertJsonCount(1, 'data');
    }

    public function test_it_can_scope_order_by_channel()
    {
        $channel = Channel::factory()->create(['name' => 'Abc']);
        $channel2 = Channel::factory()->create(['name' => 'Zwy']);

        $discussion = Discussion::factory()->create([
            'channel_id' => $channel2->id,
        ]);

        $anotherDiscussion = Discussion::factory()->create([
            'channel_id' => $channel->id
        ]);

        $this->json('get', '/api/discussions?channels=desc&perPage=1')
            ->assertJsonFragment([
                'title' => $discussion->title,
            ]);
    }

    public function test_it_can_scope_order_by_last_post_date()
    {
        $discussion = Discussion::factory()->create();
        $discussion2 = Discussion::factory()->create();
        

        Post::factory()->create([
            'discussion_id' => $discussion->id,
            'created_at' => Carbon::now(),
        ]);

       $post =  Post::factory()->create([
            'discussion_id' => $discussion->id,
            'created_at' => Carbon::now()->addYear(),
        ]);

        Post::factory()->create([
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
        $discussion = Discussion::factory()->create();
        $discussion2 = Discussion::factory()->create();

        Post::factory(5)->create([
            'discussion_id' => $discussion->id,
        ]);

        Post::factory(2)->create([
            'discussion_id' => $discussion2->id,
        ]);

        $this->json('get', '/api/discussions?activity=desc&perPage=1')
            ->assertJsonFragment([
                'id' => $discussion->id,
            ]);
    }

    public function test_it_can_scope_by_closed_at_column_true()
    {
        $discussion = Discussion::factory()->create(['closed_at' => Carbon::now()]);
        $anotherDiscussion = Discussion::factory()->create();
        
        $this->json('get', '/api/discussions?closed=true')
            ->assertJsonFragment([
                'id' => $discussion->id,
            ]);
    }

    public function test_it_can_scope_by_closed_at_column_false()
    {
        $discussion = Discussion::factory()->create(['closed_at' => Carbon::now()]);
        $anotherDiscussion = Discussion::factory()->create();
        
        $this->json('get', '/api/discussions?closed=false')
            ->assertJsonFragment([
                'id' => $anotherDiscussion->id,
            ]);
    }

    public function test_it_filters_by_no_posts_yet()
    {
        $discussion = Discussion::factory()->create();
        $posts = Post::factory(3)->create(['discussion_id' => $discussion->id]); 

        $anotherDiscussion = Discussion::factory()->create();
        $posts = Post::factory()->create(['discussion_id' => $anotherDiscussion->id]); 
        
        $this->json('get', '/api/discussions?no_posts=true')
            ->assertJsonCount(1, "data");
    }
}
