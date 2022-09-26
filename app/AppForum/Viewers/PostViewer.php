<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Message;
use App\Section;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\ClanAllianceHelper;
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
            'editor' => false,
            'section_id' => null,
            'forum_id' => null,
            'sectionsAside' => collect(),
            'user_clan' => false,
            'user_clan_moder' => false,
            'user_alliance' => false,
            'user_alliance_moder' => false,
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
        if (!is_null($user) && !is_null($user->newspaper_id) && $user->newspaper->forum_id == $post->forum_id) {
            $model['editor'] = true;
        }

        if (!is_null($user)) {
            $model['user_clan'] = ClanAllianceHelper::userClan($user, $post->topic->forum);
            $model['user_clan_moder'] = ClanAllianceHelper::userClanModer($user, $post->topic->forum);
            $model['user_alliance'] = ClanAllianceHelper::userAlliance($user, $post->topic->forum);
            $model['user_alliance_moder'] = ClanAllianceHelper::userAllianceModer($user, $post->topic->forum);
        }

        $model['section_id'] = $post->topic->forum->section_id;
        $model['forum_id'] = $post->topic->forum_id;
        $model['post'] = $post;
        $model['DATA'] = json_decode($post->DATA);
        $model['topic'] = $post->topic;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic($post->topic_id);
        $topicPage = ForumHelper::topicPage($post->topic_id, $user_role);
        $pages = $topicPage['pages'];
        $model['page'] = ForumHelper::parsePage($page, $pages);

        if (is_null($user)) return $model;
        $model['postEdit'] = ModerHelper::moderPostEdit($user->role_id, $user, $user->id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum->id, $post->topic->forum->section_id, $post->topic_id);
        $model['postModer'] = ModerHelper::moderPost($user->role_id, $post->topic->forum_id, $post->topic->forum->section_id, $user, $post->topic_id);

        return $model;
    }
}
