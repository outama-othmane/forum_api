<?php

namespace Tests\Feature\Posts;

use App\Models\Discussion;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DestroyPostTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('delete', '/api/discussions/nope/posts/233')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_discussion_doesnt_exists()
    {
        $this->jsonAs($this->generateUser(), 'delete', '/api/discussions/nope/posts/434')
            ->assertNotFound();
    }

    public function test_it_fails_if_the_post_doesnt_exists()
    {
        $discussion = factory(Discussion::class)->create();

        $this->jsonAs($this->generateUser(), 'delete', '/api/discussions/'. $discussion->slug .'/posts/434')
            ->assertNotFound();
    }

    public function test_it_fails_if_the_current_user_diff_than_the_owner()
    {
        $discussion = factory(Discussion::class)->create();
        $post = factory(Post::class)->create(['discussion_id' => $discussion->id]);

        $this->jsonAs($this->generateUser(), 'delete', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id)
            ->assertForbidden();
    }

    public function test_it_fails_if_the_discussion_is_closed()
    {
        $user = $this->generateUser();
        
        $discussion = factory(Discussion::class)->create([
            'closed_at' => Carbon::now()
        ]);
        
        $post = factory(Post::class)->create([
            'discussion_id' => $discussion->id, 
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'delete', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id)
            ->assertJsonValidationErrors(['content']);
    }

    public function test_it_deletes_the_post()
    {
        $user = $this->generateUser();
        
        $discussion = factory(Discussion::class)->create();
        
        $post = factory(Post::class)->create([
            'discussion_id' => $discussion->id, 
            'user_id' => $user->id
        ]);

        $this->jsonAs($user, 'delete', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id);

        $this->assertSoftDeleted('posts', [
            'id'=> $post->id,
        ]);
    }
}
