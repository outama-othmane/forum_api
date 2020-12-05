<?php

namespace Tests\Unit\Models\Posts;

use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_it_belongs_to_a_user()
    {
    	$post = Post::factory()->create();

    	$this->assertInstanceOf(User::class, $post->user);
    }

    public function test_it_belongs_to_a_discussion()
    {
    	$post = Post::factory()->create();

    	$this->assertInstanceOf(Discussion::class, $post->discussion);
    }

    public function test_it_has_many_votes()
    {
        $post = Post::factory()->create();

        $votes = Vote::factory(5)->create([
            'post_id' => $post->id,
        ]);

        $this->assertEquals(5, $post->votes->count());
    }

    public function test_lessVotes_method()
    {
        $post = Post::factory()->create();

        $votes = Vote::factory(5)->create([
            'post_id' => $post->id,
        ]);
        
        $user = $this->generateUser();
        $this->actingAs($user);

        $votes = Vote::factory(2)->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals(2, $post->lessVotes->count());
    }

    public function test_it_has_many_children()
    {
        $post = Post::factory()->create();
        $anotherPost = Post::factory()->create(['parent_id' => $post->id]);

        $this->assertEquals(1, $post->children->count());
    }

    public function test_if_the_current_user_can_edit_the_comment()
    {
        $user = $this->generateUser();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($post->canEdit());
    }

    public function test_if_the_current_user_can_delete_the_comment()
    {
        $user = $this->generateUser();
        $this->actingAs($user);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($post->canDelete());
    }
}
