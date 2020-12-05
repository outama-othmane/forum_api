<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Channel;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Discussion::class, function (Faker $faker) {
    return [
        'channel_id' => factory(Channel::class)->create()->id,
        // 'started_post_id' => factory(Post::class)->create()->id,
        // 'started_user_id' => factory(User::class)->create()->id,
        'title' => $title = $faker->sentence,
        'slug' => Str::slug($title)
    ];
});
