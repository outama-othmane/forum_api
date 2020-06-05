<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function generateUser()
    {
        $user = factory(User::class)->create();
    	return $user;
    }

    public function jsonAs(User $user, $method, $url, $data = [], $headers = [])
    {
    	$headers = array_merge([
    		'Authorization' => "Bearer " . $user->createToken('web_api')->plainTextToken,
    	], $headers);
    	
    	return $this->json($method, $url, $data, $headers);
    }
}
