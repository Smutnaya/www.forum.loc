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
}
