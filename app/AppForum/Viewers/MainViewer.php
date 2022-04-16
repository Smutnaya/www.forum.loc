<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Topic;
use App\Section;

class MainViewer
{
    private static function init()
    {
        return collect([
            'sections' => collect(), // id, title, description
            'posts' => collect(),
            'topics' => collect(),
        ]);
    }

    public static function index()
    {
        $model = self::init();

        // section
        $sections = Section::all();
        self::setSection($model, $sections);

        // post

        $posts = Post::all();
        self::setPost($model, $posts);

        // topic

        $topics = Topic::all();
        self::setTopic($model, $topics);

        return $model;
    }

    private static function setSection($model, $sections)
    {
        foreach($sections as $section)
        {
            $model['sections']->push([
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description
            ]);
        }
    }

    private static function setPost($model, $posts)
    {
        foreach($posts as $post)
        {
            $model['posts']->push([
            'id' => $post->id,
            'text' => $post->text
            ]);
        }
    }

    private static function setTopic($model, $topics)
    {
        foreach($topics as $topic)
        {
            $model['topics']->push([
                'title' => $topic->title
            ]);
        }
    }



}
