<?php

namespace Tests\Feature\Discussions;

use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowDiscussionTest extends TestCase
{
    public function test_it_fails_if_the_discussion_doesnt_exist()
    {
        $this->json('get', '/api/discussions/nope-lo')
            ->assertNotFound();
    }

    public function test_it_shows_the_discussion()
    {
        $discussion = Discussion::factory()->create();

        $this->json('get', '/api/discussions/' . $discussion->slug)
            ->assertJsonFragment([
                'id' => $discussion->id,
                'slug' => $discussion->slug, 
            ]);
    }
}
