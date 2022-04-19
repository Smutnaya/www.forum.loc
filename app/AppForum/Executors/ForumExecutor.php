<?php

namespace App\AppForum\Executors;

use App\Forum;
use App\AppForum\Managers\TopicManager;

class ForumExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($forumId, $input)
    {
        $out = collect();

        self::topic_valid($forumId, $input, $out);

        if(self::$result['success'])
        {
            $topicId = TopicManager::post($out['forum'], $out['text'], $out['title']);

            self::$result['topicId'] = $topicId;
            self::$result['message'] = 'OK';
        }

        //dd(self::$result['message']);
        return self::$result;
    }

    private static function topic_valid($forumId, $input, $out)
    {
        $forum = Forum::find($forumId);

        if(is_null($forum)) return self::$result['message'] = 'Razdel s topicami ne najden!!!';


        if(!isset($input['text']) || empty(trim($input['text']))) return self::$result['message'] = 'vvedite text';
        if(!isset($input['title']) || empty(trim($input['title']))) return self::$result['message'] = 'tema????';

        //dd(self::$result['message']);
        // TODO: a est li text?
        // TODO: dlinna
        // TODO: tema

        $out['forum'] = $forum;
        $out['text'] = $input['text'];
        $out['title'] = $input['title'];

        self::$result['success'] = true;
    }
}
