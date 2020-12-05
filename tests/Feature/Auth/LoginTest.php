<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->json('post', '/api/auth/login')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('post', '/api/auth/login')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_fails_if_the_credentiels_are_incorrect()
    {
        $user = User::factory()->create();

        $this->json('post', '/api/auth/login', [
            'email' => $user->email,
            'password' => $user->email,
        ])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_returns_a_token()
    {
        $user = User::factory()->create();

        $this->json('post', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertJsonStructure(['token']);
    }
}
