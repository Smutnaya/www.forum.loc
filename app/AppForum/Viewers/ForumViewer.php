<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\Topic;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class ForumViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'forumTitle' => null,
            'user' => null,
            'topics' => collect(),
            'forumId' => null,

            'pagination' => collect([
                'page' => null,
                'pages' => null,
                'forumId' => null,
            ]),
        ]);
    }

    public static function index($forumId, $user, $page)
    {
        $model = self::init();

        if(!is_null($user)) $model['user'] = $user;
        $forum = Forum::find(intval($forumId));

        if(is_null($forum)) return $model;
        $model['forumTitle'] = $forum->title;
        $model['forumId'] = $forum->id;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum(intval($forumId));

        $post_num = Topic::where('forum_id', intval($forumId))->count();

        $take = 20;
        $pages = (int) ceil($post_num / $take);
        $page = ForumHelper::parsePage($page, $pages);
        $skip = ($page - 1) * $take;

        $topics = self::getTopic(intval($forumId), $skip, $take);
        if ($topics->isEmpty()) return $model;
        self::setTopic($model, $topics);

        $model['pagination']['forumId'] = $forum->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        return $model;
    }

    public static function getTopic($forumId, $skip = null, $take = null)
    {
        if (!is_null($skip)) {
            return Topic::where('forum_id', $forumId)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
        }

        return Topic::where('forum_id', $forumId)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
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
                'title_slug' => ForumHelper::slugify($topic->title),
                'datetime' => ForumHelper::timeFormat($topic->datetime),
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
