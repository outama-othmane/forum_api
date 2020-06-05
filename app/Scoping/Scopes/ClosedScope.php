<?php

namespace App\Scoping\Scopes;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ClosedScope implements Scope
{
	public function apply(Builder $builder, $value)
	{
		$value = $this->resolveNull($value);

		if (is_null($value)) {
			return $builder;
		}

		if ($value == 'closed') {
			return $builder->whereNotNull('closed_at');
		}
		
		return $builder->whereNull('closed_at');
	}

	protected function resolveNull($value)
    {
        return Arr::get([
            'true' 	=> 'closed',
            'false' => 'unclosed'
        ], $value, NULL);
    }
}