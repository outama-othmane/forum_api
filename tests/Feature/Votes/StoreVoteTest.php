<?php

namespace Tests\Feature\Votes;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreVoteTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('post', '/api/posts/dd/votes')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_post_doesnt_exists()
    {
        $res = $this->jsonAs($this->generateUser(), 'post', '/api/posts/nope/votes')
        ->assertNotFound();
    }

    public function test_it_stores_the_vote()
    {
        $user = $this->generateUser();
        $post = factory(Post::class)->create();

        $this->jsonAs($user, 'post', '/api/posts/'. $post->id .'/votes');

        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_it_stores_the_vote_one_time_only()
    {
        $user = $this->generateUser();
        $post = factory(Post::class)->create();

        factory(Vote::class)->create($data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->jsonAs($user, 'post', '/api/posts/'. $post->id .'/votes');
        
        $this->assertEquals(1, Vote::where($data)->count());
    }
}
