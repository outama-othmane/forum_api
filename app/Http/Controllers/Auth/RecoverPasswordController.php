<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RecoverPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password'  => [
                'required', 
                (new PasswordRule)->length(6)
                    ->requireUppercase()
                    ->requireNumeric()
                    ->requireSpecialCharacter()
            ],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->broker()->reset(
            array_merge($request->only('email', 'password', 'token'), ['password_confirmation' => $request->password]),
            function ($user) use ($request) {
                
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                $user->setRememberToken(Str::random(60));
                
                event(new PasswordResetEvent($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? $this->passwordResetResponse($status)
                    : $this->failedPasswordResetResponse($status);
    }

    protected function passwordResetResponse(string $status) 
    {
        return new JsonResponse([
            'message' => Lang::get($status)
        ], 200);
    }

    protected function failedPasswordResetResponse(string $status)
    {
        throw ValidationException::withMessages(['email' => [Lang::get($status)],]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     */
    protected function broker()
    {
        return Password::broker();
    }
}
