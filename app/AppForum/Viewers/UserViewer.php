<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Role;
use App\User;
use App\Topic;
use App\Online;
use App\Section;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\Forum;

class UserViewer
{
    private static function init()
    {
        return collect([
            'sectionsAside' => collect(), // id, title, description
            'user_inf' => null,
            'user' => null,
            'user_posts' => collect(),
            'forums_block' => collect(),
            'sections_block' => collect(),
        ]);
    }

    public static function index($user_id, $user)
    {
        $model = self::init();

        // section
        if (is_null($user) || $user->role_id < 5) {
            $sectionsAside = Section::where('private', false)->get();
        } else {
            $sectionsAside = Section::all();
        }
        self::setSectionAside($model, $sectionsAside);

        $user_inf = User::find(intval($user_id));
        if (is_null($user_inf)) return $model;
        self::setUser($model, $user_inf);

        $user_posts = Post::where('user_id', intval($user_id))->orderByDesc('id')->distinct()->limit(50)->get();
        if (is_null($user_posts)) return $model;
        $user_id = 0;
        $user_role = 0;
        if (!is_null($user)) {
            $model['user'] = $user;
            $user_id = $user->id;
            $user_role = $user->role_id;
        }
        self::setUserPost($model, $user_posts, $user_id,  $user_role);
        $forums = Forum::all();
        if (is_null($forums)) return $model;
        self::setblockForum($model, $user_role, $forums);
        $sections = Section::all();
        if (is_null($sections)) return $model;
        self::setblockSection($model, $user_role, $sections);
        return $model;
    }

    private static function setUser($model, $user_inf)
    {
        $model['user_inf'] = [
            'id' => $user_inf->id,
            'name' => $user_inf->name,
            'ip' => $user_inf->ip,
            'role_id' => $user_inf->role_id,
            'ip_online' => $user_inf->online->ip,
            'role' => Role::find($user_inf->role_id)->description,
            'style' => ForumHelper::roleStyle($user_inf->role_id),
            'DATA' => json_decode($user_inf->DATA, false),
        ];

        $online = $user_inf->online->datetime;
        //dd($user_inf->online->datetime);
        if ($online < strtotime('-15 minute')) {
            $model['user_inf'] = [
                'id' => $user_inf->id,
                'name' => $user_inf->name,
                'ip' => $user_inf->ip,
                'role_id' => $user_inf->role_id,
                'ip_online' => $user_inf->online->ip,
                'role' => Role::find($user_inf->role_id)->description,
                'style' => ForumHelper::roleStyle($user_inf->role_id),
                'DATA' => json_decode($user_inf->DATA, false),
                'online' => ForumHelper::timeFormat($online)
            ];
        } else {
            $model['user_inf'] = [
                'id' => $user_inf->id,
                'name' => $user_inf->name,
                'ip' => $user_inf->ip,
                'role_id' => $user_inf->role_id,
                'ip_online' => $user_inf->online->ip,
                'role' => Role::find($user_inf->role_id)->description,
                'style' => ForumHelper::roleStyle($user_inf->role_id),
                'DATA' => json_decode($user_inf->DATA, false),
                'online' => 'online'
            ];
        }
    }
    private static function setUserPost($model, $user_posts, $user_role, $user_id)
    {
        foreach ($user_posts as $post) {
            if ($post->hide) {
                if ($user_role > 1 || $user_id == $post->user_id) {
                    $model['user_posts']->push([
                        'text' => $post->text,
                        'id' => $post->id,
                        'date' => ForumHelper::timeFormat($post->datetime),
                        'date_d' => $post->datetime,
                        'hide' => $post->hide,
                        'moderation' => $post->moderation,
                        'topic_id' => $post->topic_id,
                        'topic_title' => $post->topic->title,
                        'forum_id' => $post->topic->forum_id,
                        'forum_title' => $post->topic->forum->title,
                        'section_id' => $post->topic->forum->section_id,
                        'section_title' => $post->topic->forum->section->title,
                    ]);
                }
            } else {
                $model['user_posts']->push([
                    'text' => $post->text,
                    'id' => $post->id,
                    'date' => ForumHelper::timeFormat($post->datetime),
                    'date_d' => $post->datetime,
                    'hide' => $post->hide,
                    'moderation' => $post->moderation,
                    'topic_id' => $post->topic_id,
                    'topic_title' => $post->topic->title,
                    'forum_id' => $post->topic->forum_id,
                    'forum_title' => $post->topic->forum->title,
                    'section_id' => $post->topic->forum->section_id,
                    'section_title' => $post->topic->forum->section->title,
                ]);
            }
        }
    }

    private static function setSectionAside($model, $sections)
    {
        foreach ($sections as $section) {
            $model['sectionsAside']->push([
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description
            ]);
        }
    }

    private static function setblockForum($model, $user_role, $forums)
    {
        foreach ($forums as $forum) {
            if(ModerHelper::moderPost($user_role, $forum->id, $forum->section_id)) $model['forums_block']->push($forum);
        }
    }
    private static function setblockSection($model, $user_role, $sections)
    {
        foreach ($sections as $section) {
            if(ModerHelper::blockSection($user_role, $section)) $model['sections_block']->push($section);
        }
    }
}
