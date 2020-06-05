<?php

namespace App\Scoping\Scopes;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class ChannelScope implements Scope
{
	public function apply(Builder $builder, $value)
	{
		return $builder->whereHas('channel', function ($query) use ($value) {
			$query->where('slug', $value);
		});
	}
}