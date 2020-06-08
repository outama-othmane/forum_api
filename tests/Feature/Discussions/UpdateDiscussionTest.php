<?php

namespace Tests\Feature\Discussions;

use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateDiscussionTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('put', '/api/discussions/nope')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_discussion_doesnt_exist()
    {
        $user = $this->generateUser();

        $this->jsonAs($user, 'put', '/api/discussions/nope')
            ->assertNotFound();
    }

    public function test_it_requires_title()
    {
         $user = $this->generateUser();

        $discussion = factory(Discussion::class)->create(['started_user_id' => $user->id]);
        $this->jsonAs($user, 'put', '/api/discussions/' . $discussion->slug)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_requires_a_valid_close()
    {
         $user = $this->generateUser();

        $discussion = factory(Discussion::class)->create(['started_user_id' => $user->id]);
        $this->jsonAs($user, 'put', '/api/discussions/' . $discussion->slug, ['close' => 'f'])
            ->assertJsonValidationErrors(['close']);
    }

    public function test_it_fails_if_fails_if_the_user_isnt_the_owner()
    {
        $user = $this->generateUser();

        $discussion = factory(Discussion::class)->create();
        $this->jsonAs($user, 'put', '/api/discussions/' . $discussion->slug, ['title' => 'HELLO'])
            ->assertForbidden();
    }

    public function test_it_updates_the_discussions()
    {
        $user = $this->generateUser();

        $discussion = factory(Discussion::class)->create(['started_user_id' => $user->id]);
        $this->jsonAs($user, 'put', '/api/discussions/' . $discussion->slug, $data = [
            'title' => 'New name',
            'close' => '1'
        ]);

        $this->assertDatabaseHas('discussions', [
            'id'=> $discussion->id,
            'title'=> $data['title']
        ]);
    }
}
