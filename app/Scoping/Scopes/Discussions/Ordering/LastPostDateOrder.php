<?php

namespace App\Scoping\Scopes\Discussions\Ordering;

use App\Models\Post;
use App\Scoping\Contracts\Scope;
use App\Scoping\Scopes\Discussions\Ordering\Traits\OrderDirection;
use Illuminate\Database\Eloquent\Builder;

class LastPostDateOrder implements Scope
{
	use OrderDirection;

	public function apply(Builder $builder, $value)
	{
		return $builder->orderBy(
			
            Post::select('created_at')
            ->whereColumn('discussions.id', 'posts.discussion_id')
            ->latest()
            ->take(1),

            $this->resolveOrderDirection($value)
        );
	}
}