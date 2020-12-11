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
    	$this->validator($request->only([$this->username(), 'password']))->validate();

    	$user = User::firstWhere($this->getFieldType($request), $request->email);

    	if (!$user || !Hash::check($request->password, $user->password)) {
    		throw ValidationException::withMessages([
	            $this->username() => [
					Lang::get('auth.failed'),
				],
	        ]);
    	}

    	return ['token' => $user->createToken(self::DEVICE_NAME)->plainTextToken];
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
    		'email' 	=> ['required', 'string'],
    		'password' 	=> ['required', 'string'],
    	]);
	}
	
	protected function username()
	{
		return "email";
	}

	protected function getFieldType(Request $request)
	{
		if ( \filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL) === false )
			return 'username';
		
		return 'email';
	}
}
