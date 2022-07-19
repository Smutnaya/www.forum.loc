<?php

namespace App\AppForum\Viewers;

use App\Online;
use App\Post;
use App\Topic;
use App\Section;

class MainViewer
{
    private static function init()
    {
        return collect([
            'sections' => collect(), // id, title, description
            'onlines' => collect(),
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

        $onlines = Online::where('datetime','>=', strtotime('-15 minute'))->orderBy('datetime','desc')->get();
        //$onlines = Online::all();
        //dd($onlines);
        if(is_null($onlines)) return $model;
        $model['onlines'] = $onlines;

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
