<?php

namespace Tests\Feature\Posts;

use App\Models\Discussion;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StorePostTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('post', '/api/discussions')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_discussion_doesnt_exists()
    {
        $res = $this->jsonAs($this->generateUser(), 'post', '/api/discussions/nope/posts')
        // ;
        // dd($res);
            ->assertNotFound();
    }

    public function test_it_requires_content_field()
    {
        $discussion = Discussion::factory()->create();

        $this->jsonAs($this->generateUser(), 'post', "/api/discussions/{$discussion->slug}/posts")
            ->assertJsonValidationErrors(['content']);
    }

    public function test_it_fails_if_the_parent_id_doesnt_exist()
    {
        $discussion = Discussion::factory()->create();

        $this->jsonAs($this->generateUser(), 'post', "/api/discussions/{$discussion->slug}/posts", ['parent_id' => 'n'])
            ->assertJsonValidationErrors(['parent_id']);
    }

    public function test_it_fails_if_the_parent_id_doesnt_belong_to_the_discussion()
    {
        $post = Post::factory()->create();
        $discussion = Discussion::factory()->create();

        $this->jsonAs($this->generateUser(), 'post', "/api/discussions/{$discussion->slug}/posts", ['content' => 'my content', 'parent_id' => $post->id])
            ->assertJsonValidationErrors(['parent_id']);
    }

    public function test_it_fails_if_the_parent_id_is_soft_deleted()
    {
        $discussion = Discussion::factory()->create();
        $post = Post::factory()->create(['discussion_id' => $discussion->id]);
        $post->delete();

        $this->jsonAs($this->generateUser(), 'post', "/api/discussions/{$discussion->slug}/posts", ['content' => 'my content', 'parent_id' => $post->id])
            ->assertJsonValidationErrors(['parent_id']);
    }

    public function test_it_creates_the_post()
    {
        $user = $this->generateUser();
        $discussion = Discussion::factory()->create();
        $post = Post::factory()->create(['discussion_id' => $discussion->id]);

        $this->jsonAs($user, 'post', "/api/discussions/{$discussion->slug}/posts", [
            'content' => $content = 'Yummy content here :)',
            'parent_id' => $post->id
        ]);

        $this->assertDatabaseHas('posts', [
            'content' => $content,
            'parent_id' => $post->id,
            'user_id' => $user->id,
            'discussion_id' => $discussion->id,
        ]);
    }
}
