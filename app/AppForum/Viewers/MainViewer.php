<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Topic;
use App\Online;
use App\Message;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\ComplaintHelper;

class MainViewer
{
    private static function init()
    {
        return collect([
            'sectionsAside' => collect(), // id, title, description
            'onlines' => collect(),
            'user' => null,
            'message_new' => null,
            'news' => collect(),
            'last_posts' => collect(),
            'new_topics' => collect(),
            'complaints' => collect(),
            // 'topics' => collect(),
        ]);
    }

    public static function index($user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        $news = Topic::where('forum_id', '16')->orderBy('datetime', 'desc')->limit(30)->get();
        if ($news->count() > 0) self::setNews($model, $news);
        if (!is_null($user)) {
            $model['user'] = $user;
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
            $model['complaints'] = ComplaintHelper::review();
        }
        $user_role = ModerHelper::user_role($user);

        // последние ответы
        $last_posts = Topic::where('time_post', '!=', 'null')->orderByDesc('time_post')->distinct()->limit(20)->get();
        if ($last_posts->count() > 0) self::setLastPost($model, $last_posts, $user_role);
        // новые темы
        $new_topics = Topic::orderBy('datetime', 'desc')->limit(20)->get();
        if ($new_topics->count() > 0) self::setNewTopic($model, $new_topics);

        $onlines = Online::where('datetime', '>=', strtotime('-15 minute'))->orderBy('datetime', 'desc')->get();

        if ($onlines->count() == 0) return $model;
        self::setOnline($model, $onlines);

        return $model;
    }

    public static function setLastPost($model, $topics)
    {
        $int = 1;

        foreach ($topics as $topic) {
            $post_count = Post::where([['topic_id', $topic->id], ['moderation', false], ['hide', false]])->get();
            if ($post_count->count() > 0) {
                if (($topic->forum->section_id < 5 || $topic->forum->section_id == 6) && $int < 11 && $topic->forum_id != 16 && $topic->forum_id != 17 && $topic->forum_id != 72 && $topic->forum_id != 39 && $topic->forum_id != 40) {
                    $model['last_posts']->push([
                        'id' => $topic->id,
                        'title' => $topic->title,
                        'title_slug' => ForumHelper::slugify($topic->title),
                        'datetime' => ForumHelper::timeFormat($topic->time_post),
                        'DATA' => json_decode($topic->DATA, false),
                    ]);
                    $int++;
                }
            }
        }
    }

    public static function setNewTopic($model, $topics)
    {
        $int = 1;

        foreach ($topics as $topic) {
            $post_count = Post::where([['topic_id', $topic->id], ['moderation', false], ['hide', false]])->get();
            if ($post_count->count() > 0) {
                if (($topic->forum->section_id < 5 || $topic->forum->section_id == 6) && $int < 11 && $topic->forum_id != 16 && $topic->forum_id != 17 && $topic->forum_id != 72 && $topic->forum_id != 39 && $topic->forum_id != 40) {
                    $model['new_topics']->push([
                        'id' => $topic->id,
                        'user_id' => $topic->user_id,
                        'user_name' => $topic->user->name,
                        'avatar' => $topic->user->avatar,
                        'title' => $topic->title,
                        'title_slug' => ForumHelper::slugify($topic->title),
                        'datetime' => ForumHelper::timeFormat($topic->datetime),
                    ]);
                    $int++;
                }
            }
        }
    }

    private static function setNews($model, $news)
    {
        $news_collection = collect();

        foreach ($news as $news_post) {
            foreach ($news_post->posts as $post) {
                if (!$post->hide && !$post->moderation && !$post->topic->hide) {
                    $news_collection->push([
                        'id' => $post->id,
                        'date' => date('d.m.Y H:i', $post->datetime),
                        'datetime' => $post->datetime,
                        'title' => $post->topic->title,
                        'text' => $post->text
                    ]);
                }
            }
        }
        $model['news'] = $news_collection->sortByDesc('datetime')->take(20);
    }

    private static function setOnline($model, $onlines)
    {
        foreach ($onlines as $online) {
            $model['onlines']->push([
                'id' => $online->id,
                'user_id' => $online->user->id,
                'name' => $online->name,
                'style' => ForumHelper::roleStyle($online->user->role_id),
                'role_id' => $online->user->role_id
            ]);
        }
    }
}
