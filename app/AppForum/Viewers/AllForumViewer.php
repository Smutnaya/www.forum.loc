<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\Topic;
use App\Section;

class AllForumViewer
{
    private static function init()
    {
        return collect([
            'sections' => collect()
        ]);
    }

    public static function index()
    {
        $model = self::init();

        $sections = Section::all();
        if(is_null($sections)) return $model;

        $model['sections'] = Section::all();
        //dd(Forum::find(1)->section);

        self::setForum($model, $sections);
        return $model;

    }

    private static function setForum($model, $sections)
    {
        $model['sections'] = $sections;
    }
}
