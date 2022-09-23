<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function topic()
    {
        return $this->hasMany(Topic::class);
    }
}
