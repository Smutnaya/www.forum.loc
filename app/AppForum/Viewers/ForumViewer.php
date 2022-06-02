<?php

namespace App\AppForum\Viewers;

use App\Topic;
use App\AppForum\Helpers\BreadcrumHtmlHelper;
use App\Forum;

class ForumViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'forumTitle' => null,
            'topics' => collect()
        ]);
    }

    public static function index($forumId)
    {
        $model = self::init();

        $model['forumTitle'] = Forum::find($forumId)->title;

        $topics = Topic::where('forum_id', $forumId)->get();
        if(is_null($topics)) return $model;

        self::setForum($model, $topics);

        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum($forumId);

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
