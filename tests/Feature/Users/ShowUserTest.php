<?php

namespace Tests\Feature\Users;

use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowUserTest extends TestCase
{

    public function test_it_fails_if_the_user_doesnt_exists()
    {
        $this->jsonAs($this->generateUser(), 'get', '/api/users/nope')
            ->assertNotFound();
    }

    public function text_it_shows_user_information()
    {
        $user = $this->generateUser();
        factory(Post::class, 10)->create(['user_id'=>$user->id]);
        factory(Discussion::class, 5)->create(['started_user_id'=>$user->id]);
        
        $this->jsonAs($this->generateUser(), 'get', '/api/users/' . $user->username)
            ->assertJsonFragment([
                'id' => $user->id,
                'posts_count' => 10 ,
                'discussions_count' => 5,
            ]);
    }
}
