<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newspaper extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
