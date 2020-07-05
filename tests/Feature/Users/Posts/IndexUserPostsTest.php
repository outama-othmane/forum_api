<?php

namespace Tests\Feature\Users\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexUserPostsTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_exists()
    {
        $this->jsonAs($this->generateUser(), 'get', '/api/users/nope/posts')
            ->assertNotFound();
    }

    public function test_it_shows_latest_5_posts_of_the_user()
    {
        $user = $this->generateUser();
        
        $posts = factory(Post::class, 5)->create(['user_id' => $user->id]);

        collect($posts)->each(function ($post) use ($user) {
            $this->jsonAs($this->generateUser(), 'get', '/api/users/' . $user->username . '/posts')
            ->assertJsonFragment(['id' => $post->id]);
        });
    }
}
