<?php

namespace Tests\Feature\Search;

use App\Models\Discussion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    protected function setUp():void 
    {
        parent::setUp();

        $discussions = Discussion::factory(5)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_an_empty_list_when_query_is_empty()
    {

        $this->json('post', '/api/search?query')
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta',
                'links'
            ])
            ->assertJsonCount(0, 'data');
    }

    public function test_it_returns_the_expected_data()
    {

        $discussion = Discussion::factory()->create(['title' => "My only العربية title here!"]);
        $this->json('post', '/api/search?query=العربية')
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta',
                'links'
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $discussion->title]);
    }
}
