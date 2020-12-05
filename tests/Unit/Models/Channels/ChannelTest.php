<?php

namespace Tests\Unit\Models\Channels;

use App\Models\Channel;
use App\Models\Discussion;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    public function test_it_has_many_discussions()
    {
        $channel = Channel::factory()->create();

        Discussion::factory()->create(['channel_id' => $channel->id]);

        $this->assertInstanceOf(Discussion::class, $channel->discussions->first());
    }
}
