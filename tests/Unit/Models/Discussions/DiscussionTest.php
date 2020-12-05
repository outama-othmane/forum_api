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
    	$discussion = Discussion::factory()->create();

    	$this->assertInstanceOf(Channel::class, $discussion->channel);
    }

    public function test_it_has_many_posts()
    {
    	$discussion = Discussion::factory()->create();

    	$posts = Post::factory(2)->create([
    		'discussion_id' => $discussion->id,
    	]);

    	$this->assertEquals(2, $discussion->posts->count());
    }

    public function test_it_has_last_post()
    {
    	$discussion = Discussion::factory()->create();
    	

    	$post = Post::factory(5)->create([
    		'discussion_id' => $discussion->id,
    	]);

    	$anotherPost = Post::factory()->create([
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

    public function test_if_the_current_user_can_delete_the_discussion()
    {
        $user = $this->generateUser();
        $this->actingAs($user);

        $discussion = Discussion::factory()->create(['started_user_id' => $user->id]);

        $this->assertTrue($discussion->canDelete());
    }

    public function test_if_the_current_user_can_edit_the_discussion()
    {
        $user = $this->generateUser();
        $this->actingAs($user);

        $discussion = Discussion::factory()->create(['started_user_id' => $user->id]);

        $this->assertTrue($discussion->canEdit());
    }
}
