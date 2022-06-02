<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\AppForum\Helpers\BreadcrumHtmlHelper;
use App\Section;

class SectionViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'sectionTitle' => null,
            'forums' => collect()
        ]);
    }

    public static function index($sectionId)
    {
        $model = self::init();

        $forums = Forum::where('section_id', $sectionId)->get();
        $model['sectionTitle'] = Section::find($sectionId)->title;

        if(is_null($forums)) return $model;

        self::setForum($model, $forums);
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlSection($sectionId);

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
