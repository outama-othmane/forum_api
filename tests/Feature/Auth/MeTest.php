<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('get', '/api/auth/me')
            ->assertUnauthorized();
    }

    public function text_it_shows_user_information()
    {
        $this->jsonAs($user = $this->generateUser(), 'get', '/api/auth/me')
            ->assertJsonFragment(['id' => $user->id]);
    }
}
