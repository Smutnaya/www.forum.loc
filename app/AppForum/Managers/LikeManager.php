<?php

namespace App\AppForum\Managers;

use App\Like;

class LikeManager
{
    public static function new($post, $user, $action)
    {
        return $like = Like::create([
            'action' => $action,
            'datetime' => time(),
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public static function updata($like, $action)
    {
        return $like->fill([
            'action' => $action,
            'datetime' => time(),
        ])->save();
    }

    public static function del($like)
    {
        return $like->delete();
    }
}
