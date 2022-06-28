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
            // 'posts' => collect(),
            // 'topics' => collect(),
        ]);
    }

    public static function index()
    {
        $model = self::init();

        // section
        $sections = Section::all();
        self::setSection($model, $sections);

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

}
