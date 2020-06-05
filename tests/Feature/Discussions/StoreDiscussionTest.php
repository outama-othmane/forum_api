<?php

namespace Tests\Feature\Discussions;

use App\Models\Channel;
use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreDiscussionTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('post', '/api/discussions')
            ->assertUnauthorized();
    }

    public function test_it_requires_a_title()
    {
        $this->jsonAs($this->generateUser(), 'post', '/api/discussions')
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_requires_a_channel_id()
    {
        $this->jsonAs($this->generateUser(), 'post', '/api/discussions')
            ->assertJsonValidationErrors(['channel_id']);
    }

    public function test_it_requires_a_integer_channel_id()
    {
        $this->jsonAs($this->generateUser(), 'post', '/api/discussions', ['channel_id' => 'NOPE'])
            ->assertJsonValidationErrors(['channel_id']);
    }

    public function test_it_requires_a_valid_channel_id()
    {
        $this->jsonAs($this->generateUser(), 'post', '/api/discussions', ['channel_id' => 53])
            ->assertJsonValidationErrors(['channel_id']);
    }

    public function test_it_requires_content()
    {
        $this->jsonAs($this->generateUser(), 'post', '/api/discussions')
            ->assertJsonValidationErrors(['content']);
    }

    public function test_it_creates_discussion()
    {
        $channel = factory(Channel::class)->create();

        $this->jsonAs($this->generateUser(), 'post', '/api/discussions', [
            'channel_id' => $channel->id,
            'title' => $title = 'My title here !',
            'content' => 'not empty',
        ]);

        $this->assertDatabaseHas('discussions', [
            'title' => $title,
            'channel_id' => $channel->id,
        ]);
    }

    public function test_it_creates_a_different_slug_if_the_title_already_exists()
    {
        $title = 'My title here !';

        $channel = factory(Channel::class)->create();
        $discussion = factory(Discussion::class)->create(['title' => $title]);

        $this->jsonAs($this->generateUser(), 'post', '/api/discussions', [
            'channel_id' => $channel->id,
            'title' => $title,
            'content' => 'not empty',
        ]);

        $this->assertEquals(2, Discussion::where(['title' => $title])->count());
    }

    public function test_it_creates_the_first_post()
    {
        $channel = factory(Channel::class)->create();

        $user = $this->generateUser();

        $this->jsonAs($user, 'post', '/api/discussions', [
            'channel_id' => $channel->id,
            'title' => 'My title here !',
            'content' => $content = 'not empty',
        ]);

        $this->assertDatabaseHas('posts', [
            'content' => $content,
            'user_id' => $user->id,
        ]);
    }
}
