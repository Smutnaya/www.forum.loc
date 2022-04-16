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
        $post->save();

        // TODO: obnovit data
        //$topic->DATA

        /*
        $topic = new Topic();
        $topic->title = $request->input('title');
        $topic->text = $request->input('text');
        $topic->block = '0';
        $topic->pin = '0';
        $topic->moderation = '0';
        $topic->hide = '0';
        $topic->datatime = time();
        $topic->DATA = '0';
        $topic->forum_id = $forumId;
        $topic->user_id = auth()->user()->id;

        $topic->save();
        */
    }
}
