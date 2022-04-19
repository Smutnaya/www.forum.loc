<?php

namespace App\AppForum\Managers;

use App\Topic;


class TopicManager
{
    public static function post($forum, $text, $title)
    {
        $topic = Topic::create([
            'title' => $title,
            'text' => $text,
            'datatime' => time(),
            'user_id' => 1,
            'forum_id' => $forum->id,
        ]);

        return $topic->id;
    }
}
