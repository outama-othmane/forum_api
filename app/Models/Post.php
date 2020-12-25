<?php

namespace App\Models;

use \Parsedown;
use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory,
        SoftDeletes;
    
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'ip_addr'
    ];

    protected $withCount = [
        // 'votes'
    ];

    /*
     * Get the owner of the post
     *
     * @return App\Models\Discussion
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /*
     * Get the discussion that belongs to
     *
     * @return App\Models\Discussion
     */
    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    /*
     * Get all the votes
     *
     * @return App\Models\Vote collection
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /*
     * Get the vote of the current user
     *
     * @return App\Models\Vote collection
     */
    public function currentUserVotes()
    {
        if (!Auth::check()) {
            // We have to improve this :p
            return $this->hasMany(Vote::class)->whereNull(['user_id', 'post_id'])->limit(0);
        }
        return $this->hasMany(Vote::class)->where(['user_id' => Auth::user()->id]);
    } 

    /*
     * Get all the children
     *
     * @return App\Models\Post collection
     */
    public function children()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    /*
     * Check if the post is parent
     *
     * @return boolean
     */
    public function getIsParentAttribute()
    {
        return $this->parent_id === NULL;
    }

    /*
     * Post content transformed to HTML format
     *
     * @return string (HTML format)
     */
    public function getContentMarkdownAttribute()
    {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        return $parsedown->text($this->content);
    }

    /*
     * Check if the current user can edit this post
     *
     * @return boolean
     */
    public function canEdit()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->id === $this->user_id;
    }

    /*
     * Check if the current user can delete this post
     *
     * @return boolean
     */
    public function canDelete()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->user_id === Auth::user()->id && $this->discussion->started_post_id !== $this->id;
    }

    /*
     * Check if the current user is the author of the post
     *
     * @return boolean
     */
    public function isAuthor()
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->user_id === Auth::user()->id;
    }

    /*
     * Check if the post had been edited?
     *
     * @return boolean
     */
    public function edited()
    {
        return $this->created_at->notEqualTo($this->updated_at);
    }

    /*
     * Check if the current user liked the post
     *
     * @return boolean
     */
    public function isLiked()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->currentUserVotes->contains('user_id', '=', Auth::user()->id);
    }
}
