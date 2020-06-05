<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('get', '/api/users/3')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_user_doesnt_exists()
    {
        $this->jsonAs($this->generateUser(), 'get', '/api/users/33')
            ->assertNotFound();
    }

    public function text_it_shows_user_information()
    {
        $user = $this->generateUser();
        $this->jsonAs($this->generateUser(), 'get', '/api/users/' . $user->id)
            ->assertJsonFragment(['id' => $user->id]);
    }
}
