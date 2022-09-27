<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Message;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ComplaintHelper;

class ComplaintViewer
{
    private static function init()
    {
        return collect([
            'sectionsAside' => collect(), // id, title, description
            'user' => null,
            'complaints' => collect(),
            'message_new' => null,
        ]);
    }

    public static function index($user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (is_null($user)) return $model;
        $model['user'] = $user;
        if ($user->role_id > 1) {
            $model['complaints'] = ComplaintHelper::review();
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
        }

        return $model;
    }
}
