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

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }
}
