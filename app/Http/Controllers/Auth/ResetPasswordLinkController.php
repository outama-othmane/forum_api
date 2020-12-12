<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ResetPasswordLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware("throttle:reset-password-link");
    }

    // Inspiration from Fortify
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? $this->successfulPasswordResetLinkRequestResponse($status)
                    : $this->failedPasswordResetLinkRequestResponse($status);
    }

    protected function broker()
    {
        return Password::broker();
    }

    protected function successfulPasswordResetLinkRequestResponse(string $status) 
    {
        return new JsonResponse([
            'message' => Lang::get($status)
        ], 200);
    }

    protected function failedPasswordResetLinkRequestResponse(string $status)
    {
        throw ValidationException::withMessages(['email' => [Lang::get($status)],]);
    }
}
