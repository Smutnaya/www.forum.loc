<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\Message;
use App\Section;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\ComplaintHelper;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class SectionViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'sectionTitle' => null,
            'user' => null,
            'message_new' => null,
            'forums' => collect(),
            'sectionsAside' => collect(),
            'complaints' => collect(),
        ]);
    }

    public static function index($sectionId, $user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;
        if (!is_null($user)) {
            $model['user'] = $user;
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
            $model['complaints'] = ComplaintHelper::review();
        }

        $forums = ModerHelper::getForum($user, intval($sectionId));
        if ($forums->isEmpty()) return $model;
        $model['sectionTitle'] = Section::find(intval($sectionId))->title;
        $user_role = ModerHelper::user_role($user);
        self::setForum($model, $forums, $user_role, $user);
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlSection(intval($sectionId));

        return $model;
    }

    public static function setForum($model, $forums, $role_id, $user)
    {
        foreach ($forums as $forum) {
            if(ModerHelper::visForum($role_id, $forum->id, $forum->section_id, $user)) self::pushForum($model, $forum);
        }
    }

    private static function pushForum($model, $forum)
    {
        $model['forums']->push([
            'id' => $forum->id,
            'title' => $forum->title,
            'description' => $forum->description,
            'DATA' => json_decode($forum->DATA, false),
            'section_id' => $forum->section_id,
        ]);
    }
}
