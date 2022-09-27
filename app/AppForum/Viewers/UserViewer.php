<?php

namespace App\AppForum\Viewers;

use App\Ban;
use App\Post;
use App\Role;
use App\User;
use App\Forum;
use App\Images;
use App\Message;
use App\Section;
use App\Other_role;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\ComplaintHelper;

class UserViewer
{
    private static function init()
    {
        return collect([
            'sectionsAside' => collect(), // id, title, description
            'user_inf' => null,
            'user' => null,
            'message_new' => null,
            'roles' => false,
            'rolesInstall' => collect(),
            'user_posts' => collect(),
            'forums_block' => collect(),
            'sections_block' => collect(),
            'bans_activ' => collect(),
            'bans_old' => collect(),
            'user_other_role' => collect(),
            'other_role' => false,
            'other_role_bf' => false,
            'other_role_inf' => false,
            'other_role_bf_inf' => false,
            'complaints' => collect(),
        ]);
    }

    public static function index($user_id, $user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        $user_inf = User::find(intval($user_id));
        if (is_null($user_inf)) return $model;
        $limit = Images::where([['user_id', $user_inf->id], ['datetime', '>=', strtotime(date('Y-m-d'))]])->sum('size');
        if ($limit > 0) $limit /= 1048576;
        $limit = round($limit, 1);
        self::setUser($model, $user_inf, $limit);

        $user_id_view = 0;
        $user_role_view = 0;

        if (!is_null($user)) {
            $model['user'] = $user;
            $user_id_view = $user->id;
            $user_role_view = $user->role_id;
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
            $model['complaints'] = ComplaintHelper::review();
        }

        $user_posts = Post::where('user_id', intval($user_id))->orderByDesc('id')->distinct()->limit(50)->get();
        if ($user_posts->count() > 0) self::setUserPost($model, $user_posts, $user_role_view,  $user_id_view);

        $forums = Forum::all();
        if (!is_null($forums)) self::setblockForum($model, $user_role_view, $forums, $user);
        $sections = Section::all();
        if (!is_null($sections)) self::setblockSection($model, $user_role_view, $sections);

        $bans = Ban::where('user_id', $user_inf->id)->orderByDesc('id')->limit(50)->get();
        if ($bans->count() > 0) self::setBan($model, $bans);

        $model['roles'] = ModerHelper::roles($model['user'], $model['user_inf']);


        $rolesInstall = Role::all();
        if ($rolesInstall->count() > 0) self::setRolesInstall($model, $model['user'], $rolesInstall);

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', intval($user_id))->get();
            if ($other_roles->count() > 0) $model['other_role'] = true;
            $other_roles_bf = Other_role::where([['user_id', $user->id], ['moderation', true]])->get();
            if ($other_roles_bf->count() > 0) $model['other_role_bf'] = true;
        }
        if (!is_null($user_inf)) {
            $other_roles_inf = Other_role::where('user_id', $user_inf->id)->get();
            if ($other_roles_inf->count() > 0) {
                $model['other_role_inf'] = true;
                //$model['user_other_role'] = $other_role_inf;
                self::setUserOtherRole($model, $other_roles_inf);
                //dd($other_role_inf);
            }

            $other_role_bf_inf = Other_role::where([['user_id', $user_inf->id], ['moderation', true]])->get();
            if ($other_role_bf_inf->count() > 0) $model['other_role_bf_inf'] = true;
        }

        return $model;
    }

    private static function setUser($model, $user_inf, $limit)
    {
        $model['user_inf'] = [
            'id' => $user_inf->id,
            'name' => $user_inf->name,
            'ip' => $user_inf->ip,
            'ban_message' => $user_inf->ban_message,
            'role_id' => $user_inf->role_id,
            'ip_online' => null,
            'avatar' => $user_inf->avatar,
            'role' => Role::find($user_inf->role_id)->description,
            'style' => ForumHelper::roleStyle($user_inf->role_id),
            'DATA' => json_decode($user_inf->DATA, false),
            'online' => null,
            'limit' => $limit,
        ];

        if (!is_null($user_inf->online)) {
            $model['user_inf'] = ['ip_online' => $user_inf->online->ip];
            $online = $user_inf->online->datetime;

            if ($online < strtotime('-15 minute')) {
                $model['user_inf'] = [
                    'id' => $user_inf->id,
                    'name' => $user_inf->name,
                    'ip' => $user_inf->ip,
                    'ban_message' => $user_inf->ban_message,
                    'role_id' => $user_inf->role_id,
                    'ip_online' => $user_inf->online->ip,
                    'avatar' => $user_inf->avatar,
                    'role' => Role::find($user_inf->role_id)->description,
                    'roleModer' => Role::find($user_inf->role_id)->role,
                    'style' => ForumHelper::roleStyle($user_inf->role_id),
                    'DATA' => json_decode($user_inf->DATA, false),
                    'online' => ForumHelper::timeFormat($online),
                    'limit' => $limit,
                ];
            } else {
                $model['user_inf'] = [
                    'id' => $user_inf->id,
                    'name' => $user_inf->name,
                    'ip' => $user_inf->ip,
                    'ban_message' => $user_inf->ban_message,
                    'role_id' => $user_inf->role_id,
                    'ip_online' => $user_inf->online->ip,
                    'avatar' => $user_inf->avatar,
                    'role' => Role::find($user_inf->role_id)->description,
                    'roleModer' => Role::find($user_inf->role_id)->role,
                    'style' => ForumHelper::roleStyle($user_inf->role_id),
                    'DATA' => json_decode($user_inf->DATA, false),
                    'online' => 'online',
                    'limit' => $limit,
                ];
            }
        }
    }
    private static function setUserPost($model, $user_posts, $user_role, $user_id)
    {
        $topic_id = collect();
        foreach ($user_posts as $post) {
            if ($post->hide || $post->moderation) {
                if ($user_role > 1 || $user_id == $post->user_id) {
                    if (!$topic_id->contains($post->topic_id)) {
                        $model['user_posts']->push([
                            'text' => $post->text,
                            'id' => $post->id,
                            'date' => ForumHelper::timeFormat($post->datetime),
                            'date_d' => $post->datetime,
                            'hide' => $post->hide,
                            'moderation' => $post->moderation,
                            'topic_id' => $post->topic_id,
                            'topic_title' => $post->topic->title,
                            'forum_id' => $post->topic->forum_id,
                            'forum_title' => $post->topic->forum->title,
                            'section_id' => $post->topic->forum->section_id,
                            'section_title' => $post->topic->forum->section->title,
                        ]);
                        $topic_id->push($post->topic_id);
                    }
                }
            } else {
                if (!$topic_id->contains($post->topic_id)) {
                    $model['user_posts']->push([
                        'text' => $post->text,
                        'id' => $post->id,
                        'date' => ForumHelper::timeFormat($post->datetime),
                        'date_d' => $post->datetime,
                        'hide' => $post->hide,
                        'moderation' => $post->moderation,
                        'topic_id' => $post->topic_id,
                        'topic_title' => $post->topic->title,
                        'forum_id' => $post->topic->forum_id,
                        'forum_title' => $post->topic->forum->title,
                        'section_id' => $post->topic->forum->section_id,
                        'section_title' => $post->topic->forum->section->title,
                    ]);
                    $topic_id->push($post->topic_id);
                }
            }
        }
    }

    private static function setBan($model, $bans)
    {
        foreach ($bans as $ban) {
            if ($ban->datetime_end > time() && $ban->cancel == true) {
                $model['bans_old']->push($ban);
            }
            if ($ban->datetime_end > time() && $ban->cancel == false) {
                $model['bans_activ']->push($ban);
            }
            if ($ban->datetime_end < time()) {
                $model['bans_old']->push($ban);
            }
        }
    }

    private static function setblockForum($model, $user_role, $forums, $user)
    {
        foreach ($forums as $forum) {
            if (ModerHelper::moderForum($user_role, $forum->id, $forum->section_id, $user)) $model['forums_block']->push($forum);
        }
    }
    private static function setblockSection($model, $user_role, $sections)
    {
        foreach ($sections as $section) {
            if (ModerHelper::blockSection($user_role, $section->id)) $model['sections_block']->push($section);
        }
    }

    private static function setRolesInstall($model, $user, $rolesInstall)
    {
        if (!is_null($user)) {
            foreach ($rolesInstall as $role) {
                if ($user->role_id == 12) $model['rolesInstall']->push($role);
                if ($user->role_id == 4 && $role->id < 4) $model['rolesInstall']->push($role);
                if ($user->role_id == 9 && $role->id != 4  && $role->id < 9) $model['rolesInstall']->push($role);
                if ($user->role_id == 10 && $role->id != 4  && $role->id < 10) $model['rolesInstall']->push($role);
                if ($user->role_id == 11 && $role->id < 11) $model['rolesInstall']->push($role);
            }
        }
    }

    private static function setUserOtherRole($model, $other_roles_inf)
    {
        foreach ($other_roles_inf as $other_role) {
            if (!is_null($other_role->topic_id)) {
                $model['user_other_role']->push([
                    'id' => $other_role->id,
                    'user_id' => $other_role->user_id,
                    'moderation' => $other_role->moderation,
                    'title' => $other_role->topic->title,
                    'topic_id' => $other_role->topic_id,
                    'forum_id' => $other_role->forum_id,
                    'section_id' => $other_role->section_id,
                ]);
            }
            if (!is_null($other_role->forum_id)) {
                $model['user_other_role']->push([
                    'id' => $other_role->id,
                    'user_id' => $other_role->user_id,
                    'moderation' => $other_role->moderation,
                    'title' => $other_role->forum->title,
                    'topic_id' => $other_role->topic_id,
                    'forum_id' => $other_role->forum_id,
                    'section_id' => $other_role->section_id,
                ]);
            }
            if (!is_null($other_role->section_id)) {
                $model['user_other_role']->push([
                    'id' => $other_role->id,
                    'user_id' => $other_role->user_id,
                    'moderation' => $other_role->moderation,
                    'title' => $other_role->section->title,
                    'topic_id' => $other_role->topic_id,
                    'forum_id' => $other_role->forum_id,
                    'section_id' => $other_role->section_id,
                ]);
            }
        }
    }
}
