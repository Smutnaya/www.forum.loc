<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Section;
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
        if(!is_null($user)) $model['user'] = $user;
        $user_role = ModerHelper::user_role($user);
        // section
        if(is_null($user) || $user->role_id < 5)
        {
            $sectionsAside = Section::where('private', false)->get();
        } else {
            $sectionsAside = Section::all();
        }
        MainViewer::setSectionAside($model, $sectionsAside);
        $post = Post::find(intval($postId));
        if(is_null($post)) return $model;
        $model['post'] = $post;
        $model['DATA'] = json_decode($post->DATA);
        $model['topic'] = $post->topic;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic($post->topic_id);
        $topicPage = ForumHelper::topicPage($post->topic_id, $user_role);
        $pages = $topicPage['pages'];
        $model['page'] = ForumHelper::parsePage($page, $pages);

        $model['postEdit'] = ModerHelper::moderPostEdit($user->role_id, $post->user_id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum->id, $post->topic->forum->section_id);
        $model['postModer'] = ModerHelper::moderPost($user->role_id, $post->topic->forum_id, $post->topic->forum->section_id);

        return $model;
    }
}
