<?php

namespace App\AppForum\Managers;

use App\View;

class ViewManager
{
    public static function post($topic_id, $user_id, $ip)
    {
        $topic = View::create([
            'topic_id' => $topic_id,
            'datetime' => time(),
            'user_id' => $user_id,
            'ip' => $ip,
        ]);
    }
}
