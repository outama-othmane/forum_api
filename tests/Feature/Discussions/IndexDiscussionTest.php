<?php

namespace Tests\Feature\Discussions;

use App\Models\Discussion;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexDiscussionTest extends TestCase
{
    public function test_it_returns_a_collection_of_discussions()
    {
        $discussions = Discussion::factory(5)->create();

        collect($discussions)->each(function ($discussion) {
            $post = Post::factory()->create(['discussion_id' => $discussion->id]);
        });

        collect($discussions)->each(function ($discussion) {
            $this->json('get', '/api/discussions')
            ->assertJsonFragment([
                'id' => $discussion->id,
                'slug' => $discussion->slug, 
            ]);
        });
    }

    public function test_it_has_a_specific_structure()
    {
        $discussion = Discussion::factory()->create();
        $post = Post::factory()->create(['discussion_id' => $discussion->id]);

        $this->json('get', '/api/discussions')
            ->assertJsonStructure([
            'data',
            'meta',
            'links'
        ]);
    }
}
