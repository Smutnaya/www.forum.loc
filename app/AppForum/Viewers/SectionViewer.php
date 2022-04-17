<?php

namespace App\AppForum\Viewers;

use App\Forum;

class SectionViewer
{
    private static function init()
    {
        return collect([
            'forums' => collect()
        ]);
    }

    public static function index($sectionId)
    {
        $model = self::init();

        $forums = Forum::where('section_id', $sectionId)->get();
        if(is_null($forums)) return $model;

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
            ]);
        }
    }
}





?>
