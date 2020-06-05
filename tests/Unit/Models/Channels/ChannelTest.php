<?php

namespace Tests\Unit\Models\Channels;

use App\Models\Channel;
use App\Models\Discussion;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    public function test_it_has_many_discussions()
    {
        $channel = factory(Channel::class)->create();

        factory(Discussion::class)->create(['channel_id' => $channel->id]);

        $this->assertInstanceOf(Discussion::class, $channel->discussions->first());
    }
}
