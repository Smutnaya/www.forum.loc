<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function newspaper()
    {
        return $this->hasMany(Newspaper::class);
    }

    public function other_roles()
    {
        return $this->hasMany(Other_role::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }
}
