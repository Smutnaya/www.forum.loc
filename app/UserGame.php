<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGame extends Model
{
    protected $connection = 'mysql_game';
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'users';
}