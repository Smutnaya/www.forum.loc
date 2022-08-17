<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Topic;
use App\Online;
use App\Section;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;

class MainViewer
{
    private static function init()
    {
        return collect([
            'sectionsAside' => collect(), // id, title, description
            'onlines' => collect(),
            // 'posts' => collect(),
            // 'topics' => collect(),
        ]);
    }

    public static function index($user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        $onlines = Online::where('datetime','>=', strtotime('-15 minute'))->orderBy('datetime','desc')->get();
        //$onlines = Online::all();
        //dd($onlines);
        if(is_null($onlines)) return $model;
        self::setOnline($model, $onlines);

        return $model;
    }

    private static function setOnline($model, $onlines)
    {
        foreach($onlines as $online)
        {
            $model['onlines']->push([
                'id' => $online->id,
                'name' => $online->name,
                'style' => ForumHelper::roleStyle($online->user->role_id)
            ]);
        }
    }

}
