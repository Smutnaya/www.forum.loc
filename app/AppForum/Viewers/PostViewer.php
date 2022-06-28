<?php

namespace App\AppForum\Viewers;

use App\Post;
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
        ]);
    }

    public static function index($postId, $user)
    {
        $model = self::init();
        $post = Post::find(intval($postId));
        if(is_null($post)) return $model;
        $model['post'] = $post;
        $model['topic'] = $post->topic;

        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic($post->topic_id);

        if(is_null($user)) return $model;
        $model['user'] = $user;

        return $model;
    }
}
