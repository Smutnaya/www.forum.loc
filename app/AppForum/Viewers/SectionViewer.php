<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\AppForum\Helpers\BreadcrumHtmlHelper;
use App\AppForum\Helpers\ModerHelper;
use App\Section;

class SectionViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'sectionTitle' => null,
            'forums' => collect(),
            'sectionsAside' => collect(),
        ]);
    }

    public static function index($sectionId, $user)
    {
        $model = self::init();

        // section
        if (is_null($user) || $user->role_id < 5) {
            $sectionsAside = Section::where('private', false)->get();
        } else {
            $sectionsAside = Section::all();
        }
        MainViewer::setSectionAside($model, $sectionsAside);

        $forums = ModerHelper::getForum($user, intval($sectionId));
        if ($forums->isEmpty()) return $model;
        $model['sectionTitle'] = Section::find(intval($sectionId))->title;
        $user_role = 0;
        if(!is_null($user)) $user_role = $user->role_id;
        self::setForum($model, $forums, $user_role);
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlSection(intval($sectionId));

        return $model;
    }

    public static function setForum($model, $forums, $role_id)
    {
        foreach ($forums as $forum) {
            // if ($forum->section_id == 7) {
            //     if($role_id >= 5 && $forum->id == 65 || $forum->id == 71)
            //     {
            //         self::pushForum($model, $forum);
            //     }
            //     if($role_id >= 6 && ($forum->id == 54 || $forum->id == 55 || $forum->id == 59 || $forum->id == 60 || $forum->id == 61 || $forum->id == 66))
            //     {
            //         self::pushForum($model, $forum);
            //     }
            //     if($role_id >= 7 && ($forum->id == 62 || $forum->id == 63 || $forum->id == 64))
            //     {
            //         self::pushForum($model, $forum);
            //     }
            //     if($role_id >= 8 && ($forum->id == 56 || $forum->id == 58 || $forum->id == 67 || $forum->id == 68 || $forum->id == 69))
            //     {
            //         self::pushForum($model, $forum);
            //     }
            //     if($role_id >= 9 && ($forum->id == 70))
            //     {
            //         self::pushForum($model, $forum);
            //     }
            // } else {
            //     self::pushForum($model, $forum);
            // }

            if(ModerHelper::visForum($role_id, $forum->id, $forum->section_id)) self::pushForum($model, $forum);
        }
    }

    private static function pushForum($model, $forum)
    {
        $model['forums']->push([
            'id' => $forum->id,
            'title' => $forum->title,
            'description' => $forum->description,
            'DATA' => json_decode($forum->DATA, false)
        ]);
    }
}
