<?php

namespace Tests\Unit\Models\Discussions;

use App\Models\Channel;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\Vote;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DiscussionTest extends TestCase
{
    public function test_it_belongs_to_discussion()
    {
    	$discussion = factory(Discussion::class)->create();

    	$this->assertInstanceOf(Channel::class, $discussion->channel);
    }

    public function test_it_has_many_posts()
    {
    	$discussion = factory(Discussion::class)->create();

    	$posts = factory(Post::class, 2)->create([
    		'discussion_id' => $discussion->id,
    	]);

    	$this->assertEquals(2, $discussion->posts->count());
    }

    public function test_it_has_last_post()
    {
    	$discussion = factory(Discussion::class)->create();
    	

    	$post = factory(Post::class, 5)->create([
    		'discussion_id' => $discussion->id,
    	]);

    	$anotherPost = factory(Post::class)->create([
    		'discussion_id' => $discussion->id,
    		'created_at' => Carbon::now()->addYear(),
    	]);

    	$this->assertEquals($anotherPost->id, Discussion::find($discussion->id)->last_post_id);
    }

    public function test_it_uses_slug_as_route_key_name()
    {
        $discussion = new Discussion;
        
        $this->assertEquals('slug', $discussion->getRouteKeyName());
    }
}
