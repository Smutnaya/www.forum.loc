<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function user_to()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

}
