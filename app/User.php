<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false; // id - berem iz UserGame
    protected $with = ['online']; // Eager Loading By default


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }
    public function bans_moder()
    {
        return $this->hasMany(Ban::class, 'user_moder_id');
    }
    public function bans_cancel()
    {
        return $this->hasMany(Ban::class, 'user_cancel_id');
    }

    public function message()
    {
        return $this->hasMany(Message::class);
    }
    public function message_to()
    {
        return $this->hasMany(Message::class, 'user_id_to');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function other_roles()
    {
        return $this->hasOne(Other_role::class);
    }

    public function online()
    {
        return $this->hasOne(Online::class, 'id');
    }

    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function images()
    {
        return $this->hasMany(Images::class);
    }

    public function newspaper()
    {
        return $this->belongsTo(Newspaper::class);
    }

}
