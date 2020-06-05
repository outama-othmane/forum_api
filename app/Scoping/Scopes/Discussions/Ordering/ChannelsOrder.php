<?php

namespace App\Scoping\Scopes\Discussions\Ordering;

use App\Models\Channel;
use App\Scoping\Contracts\Scope;
use App\Scoping\Scopes\Discussions\Ordering\Traits\OrderDirection;
use Illuminate\Database\Eloquent\Builder;

class ChannelsOrder implements Scope
{
	use OrderDirection;

	public function apply(Builder $builder, $value)
	{
		return $builder->orderBy(
            Channel::select('name')
            ->whereColumn('discussions.channel_id', 'channels.id'),
            $this->resolveOrderDirection($value)
        );
	}
}