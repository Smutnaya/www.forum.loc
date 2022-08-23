<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Other_role extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
