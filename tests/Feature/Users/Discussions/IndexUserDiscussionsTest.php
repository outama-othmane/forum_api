<?php

namespace Tests\Feature\Users\Discussions;

use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexUserDiscussionsTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_exists()
    {
        $this->jsonAs($this->generateUser(), 'get', '/api/users/nope/discussions')
            ->assertNotFound();
    }

    public function test_it_shows_latest_5_discussions_of_the_user()
    {
        $user = $this->generateUser();
        
        $discussions = Discussion::factory(5)->create(['started_user_id' => $user->id]);

        collect($discussions)->each(function ($discussion) use ($user) {
            $this->jsonAs($this->generateUser(), 'get', '/api/users/' . $user->username . '/discussions')
            ->assertJsonFragment(['slug' => $discussion->slug]);
        });
    }
}
