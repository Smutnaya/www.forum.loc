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

    public function bans()
    {
        return $this->hasMany(User::class);
    }
    public function bans_moder()
    {
        return $this->hasMany(User::class, 'user_moder_id');
    }
    public function bans_cancel()
    {
        return $this->hasMany(User::class, 'user_cancel_id');
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
}
