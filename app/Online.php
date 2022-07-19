<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Online extends Model
{
    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }
}
