<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
    	'discussion_id'	=> factory(Discussion::class)->create()->id,
    	'user_id'	=> factory(User::class)->create()->id,
        'content' => $faker->paragraph,
    ];
});
