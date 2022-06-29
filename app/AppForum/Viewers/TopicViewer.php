<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Topic;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class TopicViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'user' => null,
            'forum' => null,
            'topic' => null,
            'posts' => collect()
        ]);
    }

    public static function index($topicId, $user)
    {
        $model = self::init();

        $topic = Topic::find(intval($topicId)); // eloquent
        if(is_null($topic)) return $model;
        self::setTopic($model, $topic);

        $posts = Post::where('topic_id', intval($topicId))->get();
        if($posts->isEmpty()) return $model;
        self::setPost($model, $posts);

        $model['forum'] = $topic->forum;
/*
        $filelist = array();
        if ($handle = opendir("C:/OpenServer/domains/www.forum.loc/public/images/smiley")) {
            while ($entry = readdir($handle)) {
                    $filelist[] = $entry;
            }
            closedir($handle);
        }
        dd($filelist);
*/
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic(intval($topicId));
        if(is_null($user)) return $model;
        $model['user'] = $user;

        return $model;
    }

    private static function setTopic($model, $topic)
    {
            $model['topic'] = [
                'title' => $topic->title,
                'text' => $topic->text,
                'hide' => $topic->hide,
                'block' => $topic->block,
                'pin' => $topic->pin,
                'moderation' => $topic->moderation,
                'id' => $topic->id,
                'datatime' => $topic->datatime,
                'DATA' => $topic->DATA,
                'user_id' => $topic->user_id
            ];
    }

    private static function setPost($model, $posts)
    {
        foreach($posts as $post)
        {
            $model['posts']->push([
                'text' => $post->text,
                'date' => date("d.m.Y H:i", $post->datatime),
                'hide' => $post->hide,
                'moderation' => $post->moderation,
                'DATA' => json_decode($post->DATA, false), //$post->DATA,
                'id' => $post->id,
                'user_id' => $post->user_id,
                'user_post' => $post->user

            ]);
        }
    }
}
