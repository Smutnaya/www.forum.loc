<?php

namespace App\AppForum\Viewers;

use App\Topic;

class ForumViewer
{
    private static function init()
    {
        return collect([
            'topics' => collect()
        ]);
    }

    public static function index($forumId)
    {
        $model = self::init();

        $topics = Topic::where('forum_id', $forumId)->get();
        if(is_null($topics)) return $model;

        self::setForum($model, $topics);

        return $model;

    }

    private static function setForum($model, $topics)
    {
        foreach($topics as $topic)
        {
            $model['topics']->push([
                'id' => $topic->id,
                'title' => $topic->title,
                'description' => $topic->description,
                'forum_id' => $topic->forum_id,
            ]);
        }
    }
}
