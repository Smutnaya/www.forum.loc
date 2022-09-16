<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Message;
use App\Section;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
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
            'message_new' => null,
            'DATA' => null,
            'page' => null,
            'postModer' => false,
            'postEdit' => false,
            'sectionsAside' => collect(),
        ]);
    }

    public static function index($postId, $user, $page)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (!is_null($user)) {
            $model['user'] = $user;
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
        }
        $user_role = ModerHelper::user_role($user);

        $post = Post::find(intval($postId));
        if (is_null($post)) return $model;
        $model['post'] = $post;
        $model['DATA'] = json_decode($post->DATA);
        $model['topic'] = $post->topic;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic($post->topic_id);
        $topicPage = ForumHelper::topicPage($post->topic_id, $user_role);
        $pages = $topicPage['pages'];
        $model['page'] = ForumHelper::parsePage($page, $pages);

        if (is_null($user)) return $model;
        $model['postEdit'] = ModerHelper::moderPostEdit($user->role_id, $user, $post->user_id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum->id, $post->topic->forum->section_id, $post->topic_id);
        $model['postModer'] = ModerHelper::moderPost($user->role_id, $post->topic->forum_id, $post->topic->forum->section_id, $user, $post->topic_id);

        return $model;
    }
}
