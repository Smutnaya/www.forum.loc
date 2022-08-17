<?php

namespace App\AppForum\Viewers;

use App\AppForum\Helpers\AsideHelper;
use App\Forum;
use App\Other_role;
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
            'other_roles' => collect(),
        ]);
    }

    public static function index($user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (!is_null($user))
        {
            $other_roles = Other_role::where('user_id', $user->id)->get();
            if (!is_null($other_roles)) $model['other_roles'] = $other_roles;
        }

        $sections = $sectionsAside;
        $user_role = ModerHelper::user_role($user);

        $forums = ModerHelper::getForumAll($user_role, $user);
        if($sections->isEmpty()) return $model;

        self::setSection($model, $sections);
        SectionViewer::setForum($model, $forums, $user_role, $user);
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
