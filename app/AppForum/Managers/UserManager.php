<?php

namespace App\AppForum\Managers;

use App\User;
use Illuminate\Support\Facades\Storage;

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

    public static function avatar_post($url, $user)
    {
        // if(!is_null($user->avatar))
        // {
        //     Storage::delete('/public'.$user->avatar);
        // }
        $user->fill([
            'avatar' => $url
        ])->save();
    }

    public static function avatar_del($user)
    {
        // if(!is_null($user->avatar))
        // {
        //     Storage::delete('/public'.$user->avatar);
        // }
        $user->fill([
            'avatar' => null
        ])->save();
    }

}
