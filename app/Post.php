<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function topic()
    {
        return $this->belongsTo('app\Topic');
    }
}
