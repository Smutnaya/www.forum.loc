<?php

namespace App\AppForum\Executors;

use App\Topic;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;

class TopicExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($topicId, $user, $input)
    {
        $out = collect();
        self::post_valid($topicId, $user, $input, $out);


        if(self::$result['success'])
        {
            //dd($out['check']);
            PostManager::post($out['topic'], $out['text'], $out['check'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function post_valid($topicId, $user, $input, $out)
    {
        if(is_null($user))  return self::$result['message'] = 'Пожалуйста, выполните вход на форум!';
        $topic = Topic::find($topicId);
        if(is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        if(!isset($input['text']) || empty($input['text'])) return self::$result['message'] = 'Введите текст!';

        // TODO: a est li text soobwenia?
        // TODO: dlinna

        //if(strlen($input['text']) > 13000) return self::$result['message'] = 'max dlina posta 13k simvolov';
        if(strlen($input['text']) < 2) return self::$result['message'] = 'Минимальнная длина сообщения 2 символа';
        // html encode & -> &amp;


        if(strlen($input['text']) > 13000) $input['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['topic'] = $topic;
        $out['text'] = $input['text'];
        $out['check'] = CheckedHelper::checkPost($input, $topic);

        self::$result['success'] = true;
    }
}
