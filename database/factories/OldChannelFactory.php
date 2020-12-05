<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Channel;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique()->word,
        'slug' => Str::slug($name)
    ];
});
