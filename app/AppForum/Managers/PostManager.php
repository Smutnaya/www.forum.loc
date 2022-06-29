<?php

namespace App\AppForum\Managers;

use App\Post;

class PostManager
{
    public static function post($topic, $text, $check, $user)
    {
        $post = Post::create([
            'text' => $text,
            'datatime' => time(),
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'user_id' => $user->id,
            'topic_id' => $topic->id,
        ]);

        // TODO: obnovit data
    }

    public static function edit($post, $text, $check, $user)
    {
        /* $post = Post::where('id', $post->id)
        ->update([
            'text' => $text,
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
        ]); */

        //$data = ['user_name' => $user->name, 'date' => time()];

        $post->fill([
            'text' => $text,
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'DATA' => json_encode(['user_name' => $user->name, 'date' => time()], JSON_UNESCAPED_UNICODE)
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
}
