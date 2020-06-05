<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('post', '/api/auth/logout')
            ->assertUnauthorized();
    }

    public function text_it_logs_out_the_current_user()
    {
        $this->jsonAs($user = $this->generateUser(), 'post', '/api/auth/logout');

        $this->assertEquals($user->tokens()->count(), 0);
    }
}
