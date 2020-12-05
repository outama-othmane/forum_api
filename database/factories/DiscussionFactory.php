<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DiscussionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discussion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'channel_id' => Channel::factory()->create()->id,
            // 'started_post_id' => Post::factory()->create()->id,
            // 'started_user_id' => User::factory()->create()->id,
            'title' => $title = $this->faker->sentence,
            'slug' => Str::slug($title)
        ];
    }
}
