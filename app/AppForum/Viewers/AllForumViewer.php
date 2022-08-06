<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\Topic;
use App\Section;
use App\AppForum\Helpers\ModerHelper;

class AllForumViewer
{
    private static function init()
    {
        return collect([
            'sections' => collect(),
            'forums' => collect(),
            'sectionsAside' => collect(),
        ]);
    }

    public static function index($user)
    {
        $model = self::init();

        // section
        if(is_null($user) || $user->role_id < 5)
        {
            $sectionsAside = Section::where('private', false)->get();
        } else {
            $sectionsAside = Section::all();
        }
        MainViewer::setSectionAside($model, $sectionsAside);

        $sections = $sectionsAside;
        $user_role = 0;
        if(!is_null($user)) $user_role = $user->role_id;
        $forums = ModerHelper::getForumAll($user_role);
        if($sections->isEmpty()) return $model;

        self::setSection($model, $sections);
        SectionViewer::setForum($model, $forums, $user_role);
        return $model;
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
