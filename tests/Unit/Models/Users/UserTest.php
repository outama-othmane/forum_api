<?php

namespace Tests\Unit\Models\Users;

use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_has_many_post()
    {
        $user = User::factory()->create();

        $posts = Post::factory(5)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(5, $user->posts->count());
    }

    public function test_it_has_many_votes()
    {
        $user = User::factory()->create();

        $votes = Vote::factory(5)->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(5, $user->votes->count());
    }

    public function test_it_uses_username_as_route_key_name()
    {
        $user = new User;
        
        $this->assertEquals('username', $user->getRouteKeyName());
    }

    public function test_it_has_many_discussions()
    {
        $user = User::factory()->create();

        Discussion::factory(5)->create([
            'started_user_id' => $user->id,
        ]);

        $this->assertEquals(5, $user->discussions->count());
    }
}
