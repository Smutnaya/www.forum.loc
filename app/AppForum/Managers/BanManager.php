<?php

namespace App\AppForum\Managers;

use App\Ban;

class BanManager
{
    public static function ban_forum($inc)
    {
        return $ban = Ban::create([
            'user_id' => $inc['user_ban']['id'],
            'text' => $inc['text'],
            'datetime' => time(),
            'datetime_end' => $inc['datetime_end'],
            'datetime_str' => $inc['datetime_str'],
            'forum_id' =>  $inc['forum']['id'],
            'user_moder_id' =>  $inc['user_moder']['id'],
        ]);
    }

    public static function ban_section($inc)
    {
        return $ban = Ban::create([
            'user_id' => $inc['user_ban']['id'],
            'text' => $inc['text'],
            'datetime' => time(),
            'datetime_end' => $inc['datetime_end'],
            'datetime_str' => $inc['datetime_str'],
            'section_id' =>  $inc['section']['id'],
            'user_moder_id' =>  $inc['user_moder']['id'],
        ]);
    }

    public static function ban_topic($inc)
    {
        return $ban = Ban::create([
            'user_id' => $inc['user_ban']['id'],
            'text' => $inc['text'],
            'datetime' => time(),
            'datetime_end' => $inc['datetime_end'],
            'datetime_str' => $inc['datetime_str'],
            'topic_id' =>  $inc['topic']['id'],
            'user_moder_id' =>  $inc['user_moder']['id'],
        ]);
    }

    public static function forum_out($inc)
    {
        return $ban = Ban::create([
            'user_id' => $inc['user_ban']['id'],
            'text' => $inc['text'],
            'datetime' => time(),
            'datetime_end' => $inc['datetime_end'],
            'datetime_str' => $inc['datetime_str'],
            'forum_out' =>  true,
            'user_moder_id' =>  $inc['user_moder']['id'],
        ]);
    }

    public static function cancel($ban, $inc)
    {
        $ban->fill([
            'cancel' => true,
            'user_cancel_id' => $inc['user_moder']['id'],
            'text_cancel' => $inc['text'],
            'datetime_cancel' => time(),
        ])->save();
    }
}
