<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class PostViewer
{
    private static function init()
    {
        return collect([
            'post' => null,
            'topic' => null,
            'breadcrump' => null,
            'user' => null,
            'DATA' => null,
            'page' => null,
        ]);
    }

    public static function index($postId, $user, $page)
    {
        $model = self::init();
        if(!is_null($user)) $model['user'] = $user;
        $post = Post::find(intval($postId));
        if(is_null($post)) return $model;
        $model['post'] = $post;
        $model['DATA'] = json_decode($post->DATA);
        $model['topic'] = $post->topic;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic($post->topic_id);
        $topicPage = ForumHelper::topicPage($post->topic_id);
        $pages = $topicPage['pages'];
        $model['page'] = ForumHelper::parsePage($page, $pages);

        return $model;
    }
}
