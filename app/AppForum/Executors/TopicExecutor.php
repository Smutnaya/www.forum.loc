<?php

namespace App\AppForum\Executors;

use App\Topic;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\TopicManager;

class TopicExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($topicId, $user, $input)
    {
        $out = collect();
        self::post_valid(intval($topicId), $input, $out);

        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text'];

        if(self::$result['success'])
        {
            PostManager::post($out['topic'], $out['text'], $out['check'], $user);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function post_valid($topicId, $input, $out)
    {
        $topic = Topic::find(intval($topicId));
        if(is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        if(strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['topic'] = $topic;
        $out['check'] = CheckedHelper::checkPost($input, $topic);

        self::$result['success'] = true;
    }

    public static function edit($topicId, $user, $input)
    {
        $out = collect();
        self::topicEdit_valid(intval($topicId), $input, $out);

        if(!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true; $out['title'] = $input['title'];

        if(self::$result['success'])
        {
            TopicManager::edit($out['topic'], $out['title'], $out['check'], $user);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function topicEdit_valid($topicId, $input, $out)
    {
        $topic = Topic::find(intval($topicId));
        if(is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        if(strlen($input['title']) > 13000 && !is_null($input['title'])) $out['title'] = mb_strimwidth($input['title'], 0, 100, "...");

        $out['topic'] = $topic;
        $out['check'] = CheckedHelper::checkTopic($input, $topic->forum);
        self::$result['success'] = true;
    }
}
