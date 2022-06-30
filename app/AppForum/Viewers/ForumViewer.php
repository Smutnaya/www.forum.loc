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
            'topics' => collect(),
            'sections' => collect()
        ]);
    }

    public static function index($forumId)
    {
        $model = self::init();

        $topics = Topic::where('forum_id', intval($forumId))->orderByDesc('pin')->orderByDesc('id')->get();

        if($topics->isEmpty()) return $model;

        self::setTopic($model, $topics);
        $model['forumTitle'] = Forum::find(intval($forumId))->title;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum(intval($forumId));

        return $model;

    }

    public static function topic($forumId)
    {
        $model = self::init();

        $forum = Forum::find(intval($forumId));
        if(is_null($forum)) return $model;
        $model['forumTitle'] = Forum::find(intval($forumId))->title;
        $model['sections']['moderation'] = $forum->section->moderation;
        $model['sections']['hide'] = $forum->section->hide;
        $model['sections']['id'] = $forum->section->id;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum(intval($forumId));
        return $model;
    }

    private static function setTopic($model, $topics)
    {
        foreach($topics as $topic)
        {
            $model['topics']->push([
                'id' => $topic->id,
                'title' => $topic->title,
                'datatime' => $topic->datatime,
                'hide' => $topic->hide,
                'block' => $topic->block,
                'pin' => $topic->pin,
                'moderation' => $topic->moderation,
                'DATA' => json_decode($topic->DATA, false),
                'forum_id' => $topic->forum_id,
                'user_id' => $topic->user_id,
                'user' => $topic->user->name
            ]);
        }
    }
}
