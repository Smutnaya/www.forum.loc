<?php

namespace App\AppForum\Managers;

use App\Online;

class OnlineManager
{
    public static function post($user, $ip, $url)
    {
        return $online = Online::create([
            'id' => $user->id,
            'name' => $user->name,
            'ip' => $ip,
            'datetime' => time(),
            'url' => $url,
        ]);
    }

    public static function updata($user, $ip, $url)
    {
        $online = Online::where('id', $user->id)
        ->update([
            'ip' => $ip,
            'datetime' => time(),
            'url' => $url,
        ]);
    }
}
