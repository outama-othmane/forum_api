<?php

namespace App\Models;

use App\Models\Discussion;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    public function discussions()
    {
    	return $this->hasMany(Discussion::class);
    }
}
