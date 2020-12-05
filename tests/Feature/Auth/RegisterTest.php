<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_it_requires_a_name()
    {
        $this->json('post', '/api/auth/register')
            ->assertJsonValidationErrors(['name']);
    }

    public function test_it_requires_a_email()
    {
        $this->json('post', '/api/auth/register')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('post', '/api/auth/register', ['email' => 'nope'])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_unique_email()
    {
        $user = User::factory()->create();
        $this->json('post', '/api/auth/register', ['email' => $user->email])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_an_username()
    {
        $this->json('post', '/api/auth/register')
            ->assertJsonValidationErrors(['username']);
    }

    public function test_it_requires_a_valid_username()
    {
        $this->json('post', '/api/auth/register', ['username' => 'nope.'])
            ->assertJsonValidationErrors(['username']);
    }

    public function test_it_requires_a_unique_username()
    {
        $user = User::factory()->create();
        $this->json('post', '/api/auth/register', ['username' => $user->username])
            ->assertJsonValidationErrors(['username']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('post', '/api/auth/register')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_requires_a_password_with_8chars_at_least()
    {
        $this->json('post', '/api/auth/register', ['password' => '1234567'])
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_requires_a_password_diff_than_email()
    {
        $this->json('post', '/api/auth/register', [
            'email' => $email = 'emailme@gmail.com', 
            'password' => $email,
        ])
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_creates_the_user()
    {
        $this->json('post', '/api/auth/register', [
            'email' => $email = 'emailme@gmail.com', 
            'password' => 'myPassword',
            'name' => $name = 'John',
            'username' => $username = 'john11_',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => $name,
            'username' => $username,
        ]);
    }
}
