<?php

namespace Tests\Feature\Auth;

use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordLinkTest extends TestCase
{
    public function test_it_requires_an_email()
    {
        $this->json('post', '/api/auth/password/reset')
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('post', '/api/auth/password/reset', ['email' => 'nope'])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_fails_if_the_email_doesnt_exist()
    {
        $this->json('post', '/api/auth/password/reset', ['email' => 'nope@nope.dev'])
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_sends_an_email_to_the_user()
    {
        $user = $this->generateUser();

        Notification::fake();

        $this->json('post', '/api/auth/password/reset', ['email' => $user->email])
            ->assertOk();

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$user], ResetPassword::class
        );
        
    }
}
