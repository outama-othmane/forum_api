<?php

namespace Tests\Feature\Channels;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexChannelTest extends TestCase
{
    public function test_it_returns_a_collection_of_channels()
    {
    	$channels = factory(Channel::class, 5)->create();

        collect($channels)->each(function ($channel) {
            $this->json('get', '/api/channels')
            ->assertJsonFragment([
                'id' => $channel->id,
                'slug' => $channel->slug, 
            ]);
        });
    }
    // Ignore this cuz i fetch all the channels now :)
    // public function test_it_paginated_data()
    // {
    // 	$channels = factory(Channel::class, 5)->create();
    // 	$this->json('get', '/api/channels')
    // 	->assertJsonStructure([
    //         'data',
    //         'meta',
    //         'links'
    //     ]);
    // }
}
