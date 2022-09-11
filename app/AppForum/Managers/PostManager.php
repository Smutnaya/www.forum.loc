<?php

namespace App\AppForum\Managers;

use App\Post;

class PostManager
{
    public static function post($topic, $text, $check, $user, $ip)
    {
        return $post = Post::create([
            'text' => $text,
            'ip' => $ip,
            'datetime' => time(),
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'user_id' => $user->id,
            'topic_id' => $topic->id,
            'forum_id' => $topic->forum_id,
        ]);
    }

    public static function edit($post, $text, $check, $data, $user)
    {
        /* $post = Post::where('id', $post->id)
        ->update([
            'text' => $text,
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
        ]); */

        //$data = ['user_name' => $user->name, 'date' => time()];

        return $post->fill([
            'text' => $text,
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'DATA' => $data
        ])->save();
    }

    public static function premod($post, $user)
    {
        $post->fill([
            'moderation' => 0,
        ])->save();
    }
    public static function unhide($post, $user)
    {
        $post->fill([
            'hide' => 0,
        ])->save();
    }

    public static function move($topic_id, $forum_id)
    {
        Post::where('topic_id', $topic_id)->update(['forum_id' => $forum_id]);
    }

    public static function updata($post, $data)
    {
        $post->fill([
            'DATA' => $data
        ])->save();
    }

    public static function del($post)
    {
        $post->delete();
    }
}
