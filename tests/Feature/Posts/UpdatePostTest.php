<?php

namespace Tests\Feature\Posts;

use App\Models\Discussion;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    public function test_it_fails_if_the_user_doesnt_auth()
    {
        $this->json('put', '/api/discussions/nope/posts/233')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_discussion_doesnt_exists()
    {
        $res = $this->jsonAs($this->generateUser(), 'put', '/api/discussions/nope/posts/434')
        // ;
        // dd($res);
            ->assertNotFound();
    }

    public function test_it_fails_if_the_post_doesnt_exists()
    {
        $discussion = factory(Discussion::class)->create();

        $res = $this->jsonAs($this->generateUser(), 'put', '/api/discussions/'. $discussion->slug .'/posts/434')
        // ;
        // dd($res);
            ->assertNotFound();
    }

    public function test_it_requires_content()
    {
        $discussion = factory(Discussion::class)->create();
        $post = factory(Post::class)->create(['discussion_id' => $discussion->id]);

        $res = $this->jsonAs($this->generateUser(), 'put', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id)
        // ;
        // dd($res);
            ->assertJsonValidationErrors(['content']);
    }

    public function test_it_fails_if_the_current_user_diff_than_the_owner()
    {
        $discussion = factory(Discussion::class)->create();
        $post = factory(Post::class)->create(['discussion_id' => $discussion->id]);

        $res = $this->jsonAs($this->generateUser(), 'put', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id, ['content' => 'something'])
        // ;
        // dd($res);
            ->assertForbidden();
    }

    public function test_it_fails_if_the_discussion_is_closed()
    {
        $user = $this->generateUser();
        $discussion = factory(Discussion::class)->create(['closed_at' => Carbon::now()]);
        $post = factory(Post::class)->create(['discussion_id' => $discussion->id, 'user_id' => $user->id]);

        $res = $this->jsonAs($user, 'put', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id, ['content' => 'something'])
        // ;
        // dd($res);
            ->assertJsonValidationErrors(['content']);
    }

    public function test_it_updates_the_post()
    {
        $user = $this->generateUser();
        
        $discussion = factory(Discussion::class)->create();
        
        $post = factory(Post::class)->create([
            'discussion_id' => $discussion->id, 
            'user_id' => $user->id
        ]);

        $res = $this->jsonAs($user, 'put', '/api/discussions/'. $discussion->slug .'/posts/'.$post->id, ['content' => $content = 'something']);

        $this->assertDatabaseHas('posts', [
            'id'=>$post->id,
            'content' =>$content
        ]);
    }
}
