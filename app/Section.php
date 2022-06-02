<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function forums()
    {
        return $this->hasMany(Forum::class);
    }

}
