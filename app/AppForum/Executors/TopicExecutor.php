<?php

namespace App\AppForum\Executors;

use App\Topic;
use App\AppForum\Managers\PostManager;

class TopicExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($topicId, $user, $input)
    {
        $out = collect();
        self::post_valid($topicId, $user, $input, $out);


        if(self::$result['success'])
        {
            PostManager::post($out['topic'], $out['text'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function post_valid($topicId, $user, $input, $out)
    {
        if(is_null($user))  return self::$result['message'] = 'не залогинились';
        $topic = Topic::find($topicId);
        if(is_null($topic)) return self::$result['message'] = 'Tema ne najdena!!!';

        //dd($input['text']);

        if(!isset($input['text']) || empty($input['text'])) return self::$result['message'] = 'vvedite text!';

        //dd($input);
        // TODO: a est li text soobwenia?
        // TODO: dlinna

        //if(strlen($input['text']) > 13000) return self::$result['message'] = 'max dlina posta 13k simvolov';
        if(strlen($input['text']) < 2) return self::$result['message'] = 'min dlina posta 2 simvola';
        // html encode & -> &amp;


        if(strlen($input['text']) > 13000) $input['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['topic'] = $topic;
        $out['text'] = $input['text'];

        self::$result['success'] = true;
    }
}
