<?php

namespace App\Scoping\Scopes\Discussions\Ordering\Traits;

use Illuminate\Support\Arr;

trait OrderDirection 
{

	protected function resolveOrderDirection($direction)
    {
        return Arr::get([
            'desc' => 'desc',
            'asc' => 'asc'
        ], $direction, 'asc');
    }
}