<?php

namespace App\AppForum\Helpers;

use function PHPUnit\Framework\isNull;

class CheckedHelper
{
    public static function checkTopic($input, $forum)
    {
        $out = [
            'pin' => 0,
            'block' => 0,
            'hide' => $forum->section->hide,
            'moder' => $forum->section->moderation
        ];

        if(!isset($input['check'])) return $out;

        $out = self::setCheck($input, $out);
        return $out;
    }

    public static function checkPostTopic($input, $topic)
    {
        $out = [
            'pin' => 0,
            'block' => 0,
            'hide' => $topic->hide,
            'moder' => $topic->moderation
        ];

        if(!isset($input['check'])) return $out;

        $out = self::setCheck($input, $out);
        return $out;
    }

    public static function checkPost($input, $topic)
    {
        $out = [
            'pin' => 0,
            'block' => 0,
            'hide' => $topic->hide,
            'moder' => $topic->moderation
        ];

        if(!isset($input['check'])) return $out;

        $out = self::setCheck($input, $out);
        return $out;
    }

    public static function setCheck($input, $out)
    {
        foreach($input['check'] as $inp)
        {
            $out[$inp] = 1;
        }
        return $out;
    }
}
