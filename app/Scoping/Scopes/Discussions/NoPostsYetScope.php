<?php

namespace App\Scoping\Scopes\Discussions;

use App\Models\Post;
use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NoPostsYetScope implements Scope
{
	public function apply(Builder $builder, $value)
	{
		if (!$this->resolveValue($value)) {
			return;
		}

		// SELECT discussions.* FROM discussions WHERE (SELECT count(posts.id) FROM posts WHERE posts.discussion_id = discussions.id) <= 1 ORDER BY created_at;
		$posts = Post::selectRaw('count(*)')
			->whereColumn('posts.discussion_id', 'discussions.id')->toSql();
		
		return $builder->whereRaw("(" . $posts . ") = 1");
	}

	public function resolveValue($value)
	{
		return Str::lower($value) === 'true';
	}
}