<?php

namespace App\Scoping\Scopes\Discussions\Ordering;

use App\Models\Post;
use App\Scoping\Contracts\Scope;
use App\Scoping\Scopes\Discussions\Ordering\Traits\OrderDirection;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Builder;

class ActivityOrder implements Scope
{
	use OrderDirection;

	public function apply(Builder $builder, $value)
    {
        return $builder->orderBy(
            'posts_count',
            $this->resolveOrderDirection($value)
        );
    }
}