<?php

namespace App\Models;

use App\Models\Discussion;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    public function getAvatarAttribute()
    {
        return sprintf("https://www.gravatar.com/avatar/%s?s=%d&d=%s",
            md5($this->email), // Hash
            180, // Size
            "mp" // Default
        );
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'started_user_id');
    }
}
