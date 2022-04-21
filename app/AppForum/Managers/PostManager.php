<?php

namespace App\AppForum\Managers;

use App\Post;

class PostManager
{
    public static function post($topic, $text, $user)
    {
        $post = Post::create([
            'text' => $text,
            'datatime' => time(),
            'user_id' => $user->id,
            'topic_id' => $topic->id,
        ]);



        // TODO: obnovit data
        //$topic->DATA

    }
}
