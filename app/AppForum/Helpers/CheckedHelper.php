<?php

namespace App\AppForum\Helpers;

class CheckedHelper
{
    public static function checkTopic($input, $forum)
    {
        $out = [
            'pin' => 0,
            'block' => 0,
            'hide' => 0,
            'moder' => 0,
            'private' => 0
        ];

        if($forum->section->moderation || $forum->moderation) $out['moder'] = 1;
        if($forum->section->hide || $forum->hide) $out['hide'] = 1;

        if(!isset($input['check'])) return $out;

        $out = self::setCheck($input, $out);
        return $out;
    }
    public static function checkTopicEdit($input)
    {
        $out = [
            'pin' => 0,
            'block' => 0,
            'hide' => 0,
            'moder' => 0,
            'private' => 0
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

        if(!isset($input['check'])) {
            if($topic->moderation) $out['moder'] = 1;
            if($topic->hide) $out['hide'] = 1;
            return $out;
        }

        $out = self::setCheck($input, $out);
        return $out;
    }

    public static function checkPost($input, $topic)
    {
        $out = [
            'pin' => 0,
            'block' => 0,
            'hide' => 0,
            'moder' => 0
        ];

        if(!isset($input['check'])) {
            if($topic->moderation) $out['moder'] = 1;
            if($topic->hide) $out['hide'] = 1;
            return $out;
        }

        $out = self::setCheck($input, $out);
        return $out;
    }
    public static function checkPostEdit($input, $topic, $post)
    {
        $out = [
            'hide' => 0,
            'moder' => 0
        ];

        if(!isset($input['check'])) {
            if($post->moderation) $out['moder'] = 1;
            if($post->hide) $out['hide'] = 1;
            return $out;
        }

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
