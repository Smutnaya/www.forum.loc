<?php

namespace App\AppForum\Managers;

use App\Topic;

class TopicManager
{
    public static function post($forum, $title, $check, $user)
    {
        //dd($check);
        $topic = Topic::create([
            'title' => $title,
            'datetime' => time(),
            'user_id' => $user->id,
            'pin' => $check['pin'],
            'block' => $check['block'],
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'forum_id' => $forum->id,
        ]);

        return $topic;
    }

    public static function edit($topic, $title, $check, $user)
    {
        $topic->fill([
            'title' => $title,
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'pin' => $check['pin'],
            'block' => $check['block']
        ])->save();
    }

    public static function dataedit($topic, $DATA)
    {
        $topic->fill([
            'DATA' => $DATA
        ])->save();
    }

    public static function move($topic, $forum_id)
    {
        $topic->fill([
            'forum_id' => $forum_id
        ])->save();

        return $topic;
    }
}
