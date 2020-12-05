<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DiscussionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = User::all();
    	$channels = Channel::all();

        Discussion::factory(100)->make()->each(function ($discussion) use($users, $channels) {
        	
        	$discussion->channel_id = $channels->random()->id;

        	$discussion->save();

            Post::factory(rand(1, 50))->make()->each(function ($post) use ($discussion, $users) {
                $post->user_id = $users->random()->id;
                $post->discussion_id = $discussion->id;

                $post->save();

                Vote::factory(rand(1, 10))->make()->each(function ($vote) use($post, $users) {

                    $vote->user_id = $users->random()->id;
                    $vote->post_id = $post->id;

                    $vote->save();
                });
            });

            $firstPost = $discussion->posts()->oldest()->first();
            $discussion->started_user_id = $firstPost->user_id;
            $discussion->started_post_id = $firstPost->id;
            $discussion->save();
        });
    }
}
