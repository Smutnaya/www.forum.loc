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

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }

    public function other_roles()
    {
        return $this->hasMany(Other_role::class);
    }
}
