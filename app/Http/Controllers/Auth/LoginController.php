<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
	protected const DEVICE_NAME = 'web_api';

    public function login(Request $request)
    {
    	$this->validator($request->only(['email', 'password']))->validate();

    	$user = User::firstWhere('email', $request->email);

    	if (!$user || !Hash::check($request->password, $user->password)) {
    		throw ValidationException::withMessages([
	            'email' => [Lang::get('auth.failed')],
	        ]);
    	}

    	return ['token' => $user->createToken(self::DEVICE_NAME)->plainTextToken];
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
    		'email' 	=> ['required', 'string', 'email'],
    		'password' 	=> ['required', 'string'],
    	]);
    }
}
