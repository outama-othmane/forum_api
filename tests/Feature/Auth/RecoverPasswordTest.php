<?php

namespace Tests\Feature\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecoverPasswordTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->json('post', '/api/auth/password/recover')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('post', '/api/auth/password/recover')
            ->assertJsonValidationErrors(['password']);
    }

    public function test_it_requires_a_token()
    {
        $this->json('post', '/api/auth/password/recover')
            ->assertJsonValidationErrors(['token']);
    }

    public function test_it_fails_if_the_email_doesnt_match_with_the_token()
    {
        $user = $this->generateUser();
        $anotherUser = $this->generateUser();

        $token = Str::random(60);

        $password = "2020@Sucks";

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at'    => now(),
        ]);
        
        $this->json('post', '/api/auth/password/recover', ['token' => $token, 'password' => $password, 'email' => $anotherUser->email])
            ->assertJsonValidationErrors(['email']);

    }

    public function test_it_updates_the_password()
    {
        $user = $this->generateUser();

        $token = Str::random(60);

        $password = "2020@Sucks";

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at'    => now(),
        ]);
        
        Event::fake();
        
        $this->json('post', '/api/auth/password/recover', [
            'token' => $token, 
            'password' => $password, 
            'email' => $user->email
        ])
        ->assertOk();

        // Assert a notification was sent to the given users...
        Event::assertDispatched(
            PasswordReset::class
        );
        
    }
}
