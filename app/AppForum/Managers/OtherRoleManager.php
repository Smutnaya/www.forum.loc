<?php

namespace App\AppForum\Managers;

use App\Ban;
use App\Other_role;

class OtherRoleManager
{

    public static function role_section($inc)
    {
        Other_role::create([
            'user_id' => $inc['user_role']['id'],
            'datetime' => time(),
            'section_id' =>  $inc['section']['id'],
            'moderation' =>  $inc['moderation'],
        ]);
    }

    public static function role_forum($inc)
    {
        Other_role::create([
            'user_id' => $inc['user_role']['id'],
            'datetime' => time(),
            'forum_id' =>  $inc['forum']['id'],
            'moderation' =>  $inc['moderation'],
        ]);
    }

    public static function role_topic($inc)
    {
        Other_role::create([
            'user_id' => $inc['user_role']['id'],
            'datetime' => time(),
            'topic_id' =>  $inc['topic']['id'],
            'moderation' =>  $inc['moderation'],
        ]);
    }

    public static function moder_true($role)
    {
        $role->fill([
            'moderation' => true,
        ])->save();
    }
    public static function moder_false($role)
    {
        $role->fill([
            'moderation' => false,
        ])->save();
    }

    public static function del($role)
    {
        $role->delete();
    }
}
