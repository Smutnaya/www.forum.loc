<?php

namespace App\AppForum\Managers;

use App\Post;

class PostManager
{
    public static function post($topic, $text)
    {
        $post = Post::create([
            'text' => $text,
            'datatime' => time(),
            'user_id' => 1,
            'topic_id' => $topic->id,
        ]);



        // TODO: obnovit data
        //$topic->DATA

    }
}
