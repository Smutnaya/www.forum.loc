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
            'breadcramp' => null,
            'topic' => null,
            'posts' => collect()
        ]);
    }

    public static function index($topicId)
    {
        $model = self::init();

        $topic = Topic::find($topicId); // eloquent
        if(is_null($topic)) return $model;

        self::setTopic($model, $topic);

        $posts = Post::where('topic_id', $topicId)->get();
        //dd($posts);
        self::setPost($model, $posts);

        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic($topicId);


        return $model;
    }

    private static function setTopic($model, $topic)
    {

            $model['topic'] = [
                'title' => $topic->title,
                'text' => $topic->text,
                'id' => $topic->id
            ];


        //dd($model);

    }

    private static function setPost($model, $posts)
    {
        foreach($posts as $post)
        {
            $model['posts']->push([
                'text' => $post->text
            ]);
        }
        //dd($model);

    }






}





?>
