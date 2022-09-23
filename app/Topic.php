<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function other_roles()
    {
        return $this->hasMany(Other_role::class);
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
