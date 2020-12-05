<?php

namespace Tests\Feature\Discussions;

use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestroyDiscussionTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('delete', '/api/discussions/nope')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_discussion_doesnt_exist()
    {
        $user = $this->generateUser();

        $this->jsonAs($user, 'delete', '/api/discussions/nope')
            ->assertNotFound();
    }

    public function test_it_fails_if_fails_if_the_user_isnt_the_owner()
    {
        $user = $this->generateUser();

        $discussion = Discussion::factory()->create();
        $this->jsonAs($user, 'delete', '/api/discussions/' . $discussion->slug)
            ->assertForbidden();
    }

    public function test_it_destroys_the_discussions()
    {
        $user = $this->generateUser();

        $discussion = Discussion::factory()->create(['started_user_id' => $user->id]);
        $this->jsonAs($user, 'delete', '/api/discussions/' . $discussion->slug);

        $this->assertSoftDeleted('discussions', [
            'id'=> $discussion->id,
        ]);
    }
}
