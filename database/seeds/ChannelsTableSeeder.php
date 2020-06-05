<?php

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::create(['name' => 'Assistance', 'slug' => 'assistance']);
        Channel::create(['name' => 'Eloquent', 'slug' => 'eloquent']);
        Channel::create(['name' => 'Envoyer', 'slug' => 'envoyer']);
        Channel::create(['name' => 'Feedback', 'slug' => 'feedback']);
        Channel::create(['name' => 'Forge', 'slug' => 'forge']);
        Channel::create(['name' => 'General', 'slug' => 'general']);
        Channel::create(['name' => 'Guides', 'slug' => 'guides']);
        Channel::create(['name' => 'JavaScript', 'slug' => 'javascript']);
        Channel::create(['name' => 'Laravel', 'slug' => 'laravel']);
        Channel::create(['name' => 'Lumen', 'slug' => 'lumen']);
        Channel::create(['name' => 'Mix', 'slug' => 'mix']);
        Channel::create(['name' => 'Spark', 'slug' => 'spark']);
    }
}
