<?php

namespace App\AppForum\Managers;

use App\Topic;

class TopicManager
{
    public static function post($forum, $title, $check, $time_post, $news, $user)
    {
        $topic = Topic::create([
            'title' => $title,
            'datetime' => time(),
            'time_post' => $time_post,
            'user_id' => $user->id,
            'pin' => $check['pin'],
            'block' => $check['block'],
            'hide' => $check['hide'],
            'private' => $check['private'],
            'news_id' => $news,
            'moderation' => $check['moder'],
            'forum_id' => $forum->id,
        ]);

        return $topic;
    }

    public static function edit($topic, $title, $check, $forum_data, $user)
    {
        $topic->fill([
            'title' => $title,
            'hide' => $check['hide'],
            'moderation' => $check['moder'],
            'pin' => $check['pin'],
            'block' => $check['block'],
            'private' => $check['private'],
            'DATA' => $forum_data,
        ])->save();

        return $topic;
    }

    public static function dataedit($topic, $DATA)
    {
        $topic->fill([
            'DATA' => $DATA
        ])->save();

        return $topic;
    }

    public static function premod_topic($topic, $moder)
    {
        $topic->fill([
            'moderation' => $moder
        ])->save();

        return $topic;
    }

    public static function lastPostEdit($topic, $last_post)
    {
        $topic->fill([
            'time_post' => $last_post
        ])->save();

        return $topic;
    }

    public static function move($topic, $forum_id)
    {
        $topic->fill([
            'forum_id' => $forum_id
        ])->save();

        return $topic;
    }
}
