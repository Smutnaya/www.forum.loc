<?php

namespace App\AppForum\Executors;

use App\Topic;
use App\AppForum\Managers\PostManager;

class TopicExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($topicId, $input)
    {
        $out = collect();
        self::post_valid($topicId, $input, $out);

        if(self::$result['success'])
        {
            PostManager::post($out['topic'], $out['text']);

            self::$result['message'] = 'OK';
        }

        return self::$result;
    }

    private static function post_valid($topicId, $input, $out)
    {
        $topic = Topic::find($topicId);
        if(is_null($topic)) return self::$result['message'] = 'Tema ne najdena!!!';

        if(!isset($input['text']) || empty(trim($input['text']))) return self::$result['message'] = 'vvedite text';

        //dd($input);
        // TODO: a est li text soobwenia?
        // TODO dlinna
        // html encode & -> &amp;

        $out['topic'] = $topic;
        $out['text'] = $input['text'];

        self::$result['success'] = true;
    }
}
