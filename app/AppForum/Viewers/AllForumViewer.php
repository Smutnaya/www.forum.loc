<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\Topic;
use App\Message;
use App\Section;
use App\Other_role;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\ComplaintHelper;

class AllForumViewer
{
    private static function init()
    {
        return collect([
            'sections' => collect(),
            'forums' => collect(),
            'user' => null,
            'message_new' => null,
            'sectionsAside' => collect(),
            'other_roles' => collect(),
            'last_posts' => collect(),
            'new_topics' => collect(),
            'complaints' => collect(),
        ]);
    }

    public static function index($user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();
            if ($other_roles->count() > 0) $model['other_roles'] = $other_roles;

            $model['user'] = $user;
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
            $model['complaints'] = ComplaintHelper::review();
        }

        $sections = $sectionsAside;
        $user_role = ModerHelper::user_role($user);

        // последние ответы
        $last_posts = Topic::where('time_post', '!=', 'null')->orderBy('time_post', 'desc')->distinct()->limit(20)->get();
        if ($last_posts->count() > 0) MainViewer::setLastPost($model, $last_posts);
        // новые темы
        $new_topics = Topic::orderBy('datetime', 'desc')->limit(20)->get();
        if ($new_topics->count() > 0) MainViewer::setNewTopic($model, $new_topics);

        $forums = ModerHelper::getForumAll($user_role, $user);
        if ($sections->isEmpty()) return $model;

        self::setSection($model, $sections);
        SectionViewer::setForum($model, $forums, $user_role, $user);
        return $model;
    }
    private static function setSection($model, $sections)
    {
        foreach ($sections as $section) {
            $model['sections']->push([
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description,
                'forums' => $section->forums
            ]);
        }
    }
}
