<?php

namespace App\AppForum\Managers;

use App\Comment;

class CommentManager
{
    public static function post($topic_id, $text, $user_id, $ip)
    {
        return $comment = Comment::create([
            'text' => $text,
            'ip' => $ip,
            'datetime' => time(),
            'user_id' => $user_id,
            'topic_id' => $topic_id,
        ]);
    }

    // public static function edit($post, $text, $check, $data, $user)
    // {
    //     return $post->fill([
    //         'text' => $text,
    //         'hide' => $check['hide'],
    //         'moderation' => $check['moder'],
    //         'DATA' => $data
    //     ])->save();
    // }

    // public static function premod($post, $user)
    // {
    //     $post->fill([
    //         'moderation' => 0,
    //     ])->save();
    // }
    // public static function unhide($post, $user)
    // {
    //     $post->fill([
    //         'hide' => 0,
    //     ])->save();
    // }

    public static function del($comment)
    {
        $comment->delete();
    }
}
