<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Faker\Generator as Faker;

$factory->define(Vote::class, function (Faker $faker) {
    return [
        'post_id'	=> factory(Post::class)->create()->id,
    	'user_id'	=> factory(User::class)->create()->id,
    ];
});
