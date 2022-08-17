<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function user_moder()
    {
        return $this->belongsTo(User::class, 'user_moder_id');
    }
    public function user_cancel()
    {
        return $this->belongsTo(User::class, 'user_cancel_id');
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
