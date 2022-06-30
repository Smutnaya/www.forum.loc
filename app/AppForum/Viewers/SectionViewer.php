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
        $forums = Forum::where('section_id', intval($sectionId))->get();
        if($forums->isEmpty()) return $model;
        $model['sectionTitle'] = Section::find(intval($sectionId))->title;
        self::setForum($model, $forums);
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlSection(intval($sectionId));

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
                'DATA' => json_decode($forum->DATA, false)
            ]);
        }
    }
}





?>
