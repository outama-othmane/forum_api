<?php

namespace Tests\Unit\Models\Votes;

use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;

class VoteTest extends TestCase
{
    public function test_it_belongs_to_a_user()
    {
    	$vote = factory(Vote::class)->create();

    	$this->assertInstanceOf(User::class, $vote->user);
    }

    public function test_it_belongs_to_a_post()
    {
    	$vote = factory(Vote::class)->create();

    	$this->assertInstanceOf(Post::class, $vote->post);
    }
}
