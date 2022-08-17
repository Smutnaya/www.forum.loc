<?php

namespace App\AppForum\Managers;

use App\User;

class UserManager
{
    public static function dataedit($user, $DATA)
    {
        $user->fill([
            'DATA' => $DATA
        ])->save();
    }
    public static function role($user, $inp)
    {
        $user->fill([
            'role_id' => $inp['role']->id
        ])->save();
    }
}
