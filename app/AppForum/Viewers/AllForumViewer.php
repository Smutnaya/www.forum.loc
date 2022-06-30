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
            'sections' => collect(),
            'forums' => collect()
        ]);
    }

    public static function index()
    {
        $model = self::init();

        $sections = Section::all();
        $forums = Forum::all();
        if($sections->isEmpty()) return $model;

        //$model['sections'] = Section::all();
        //dd(Forum::find(1)->section);

        self::setSection($model, $sections);
        self::setForum($model, $forums);
        return $model;

    }

    private static function setForum($model, $forums)
    {
        foreach($forums as $forum)
        {
            $model['forums']->push([
                'id' => $forum->id,
                'title' => $forum->title,
                'description' => $forum->description,
                'section_id' => $forum->section_id,
                'section_title' => $forum->section->title,
                'DATA' => json_decode($forum->DATA, false)
            ]);
        }
    }
    private static function setSection($model, $sections)
    {
        foreach($sections as $section)
        {
            $model['sections']->push([
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description,
                'forums' => $section->forums
            ]);
        }
    }
}
