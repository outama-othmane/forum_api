<?php

namespace Tests\Feature\Votes;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteVoteTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('delete', '/api/posts/dd/votes/hello')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_post_doesnt_exists()
    {
        $res = $this->jsonAs($this->generateUser(), 'delete', '/api/posts/nope/votes/hello')
        ->assertNotFound();
    }

    public function test_it_deletes_the_vote()
    {
        $user = $this->generateUser();
        $post = factory(Post::class)->create();

        factory(Vote::class)->create($data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->jsonAs($user, 'delete', '/api/posts/'. $post->id .'/votes/d');

        $this->assertSoftDeleted('votes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
