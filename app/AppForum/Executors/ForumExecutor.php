<?php

namespace App\AppForum\Executors;

use App\Forum;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\TopicManager;
use App\AppForum\Executors\BaseExecutor;
use Symfony\Component\Console\Input\Input;

class ForumExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($forumId, $user, $input)
    {
        $out = collect();

        self::topic_valid(intval($forumId), $input, $out);
        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text']; $out['title'] = $input['title'];

        if(self::$result['success'])
        {
            $topic = TopicManager::post($out['forum'], $out['title'], $out['check'], $user);
            self::$result['topicId'] = $topic->id;
            $out['check'] = CheckedHelper::checkPostTopic($input, $topic);
            PostManager::post($topic, $out['text'], $out['check'], $user);
            self::$result['message'] = 'OK';
        }
        return self::$result;
    }

    private static function topic_valid($forumId, $input, $out)
    {
        $forum = Forum::find(intval($forumId));

        if(is_null($forum)) return self::$result['message'] = 'Раздел с темами не найден';
        if(mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['forum'] = $forum;
        $out['check'] = CheckedHelper::checkTopic($input, $forum);
        self::$result['success'] = true;
    }

    public static function forum ($forumId, $user)
    {
        self::forum_valid($forumId);
        if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;

        if(self::$result['success']) self::$result['message'] = 'OK';
        return self::$result;
    }

    private static function forum_valid($forumId)
    {
        $forum = Forum::find(intval($forumId));
        if(is_null($forum)) return self::$result['message'] = 'Раздел с темами не найден';
        self::$result['success'] = true;
    }

}
