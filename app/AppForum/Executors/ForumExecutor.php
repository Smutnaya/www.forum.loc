<?php

namespace App\AppForum\Executors;

use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\PostManager;
use App\Forum;
use App\AppForum\Managers\TopicManager;
use Symfony\Component\Console\Input\Input;

class ForumExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($forumId, $user, $input)
    {
        $out = collect();

        //dd($input);

        self::topic_valid($forumId, $input, $out, $user);

        if(self::$result['success'])
        {
            $topic = TopicManager::post($out['forum'], $out['title'], $out['check'], $user);
            self::$result['topicId'] = $topic->id;
            $out['check'] = CheckedHelper::checkPostTopic($input, $topic);

            PostManager::post($topic, $out['text'], $out['check'], $user);

            self::$result['message'] = 'OK';
        }

        //dd(self::$result['message']);
        return self::$result;
    }

    private static function topic_valid($forumId, $input, $out, $user)
    {
        //dd($user);
        if(is_null($user))  return self::$result['message'] = 'Пожалуйста, выполните вход на форум!';

        $forum = Forum::find($forumId);

        if(is_null($forum)) return self::$result['message'] = 'Раздел с темами не найден';


        if(!isset($input['text']) || empty(trim($input['text']))) return self::$result['message'] = 'Введите текст';
        if(!isset($input['title']) || empty(trim($input['title']))) return self::$result['message'] = 'Введите название темы';

        //dd(self::$result['message']);
        // TODO: a est li text?
        // TODO: dlinna
        // TODO: tema

        $out['forum'] = $forum;
        $out['text'] = $input['text'];
        $out['title'] = $input['title'];
        $out['check'] = CheckedHelper::checkTopic($input, $forum);
        self::$result['success'] = true;
    }

    public static function forum ($forumId, $user)
    {
        self::forum_valid($forumId, $user);
        if(self::$result['success']) self::$result['message'] = 'OK';
        return self::$result;
    }

    private static function forum_valid($forumId, $user)
    {
        if(is_null($user))  return self::$result['message'] = 'Пожалуйста, выполните вход на форум!';

        $forum = Forum::find($forumId);
        if(is_null($forum)) return self::$result['message'] = 'Раздел с темами не найден';

        self::$result['success'] = true;
    }

}
