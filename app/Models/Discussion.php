<?php

namespace App\Models;

use App\Models\Channel;
use App\Models\Post;
use App\Models\Traits\CanBeScopped;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Discussion extends Model
{
    use HasFactory,
        CanBeScopped,
        Searchable,
        SoftDeletes;
    
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
        return !is_null($this->closed_at);
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        return Arr::only($array, ['id', 'title']);
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with(['channel']);
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return true;
        // return $this->isPublished();
    }
}
