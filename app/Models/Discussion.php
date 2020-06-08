<?php

namespace App\Models;

use App\Models\Channel;
use App\Models\Post;
use App\Models\Traits\CanBeScopped;
use App\Models\User;
use App\Scoping\Scoper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Discussion extends Model
{
    use CanBeScopped, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug',
    ];

    protected $withCount = [
        'posts',
    ];

    protected $casts = [
        'closed_at' => 'timestamp',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('withLastPostId', function ($query) {
            $query->withLastPostId();
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function channel()
    {
    	return $this->belongsTo(Channel::class);
    }

    public function lastPost()
    {
        return $this->belongsTo(Post::class, 'last_post_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->oldest();
    }

    public function getIsClosedAttribute()
    {
        return !($this->closed_at === NULL);
    }

    public function scopeWithLastPostId($query)
    {
        $query->addSelect(['last_post_id' => 
            Post::select('id')
            ->whereColumn('discussions.id', 'posts.discussion_id')
            ->latest()
            ->limit(1)
        ]);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'started_user_id');
    }

    // public function isAuthor()
    // {
    //     if (!Auth::check()) {
    //         return false;
    //     }

    //     return $this->author->id === Auth::user()->id;
    // }

    public function canDelete()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->started_user_id === Auth::user()->id;
    }

    public function canEdit()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->started_user_id === Auth::user()->id;
    }
}
