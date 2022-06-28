<?php

namespace App\AppForum\Executors;

use App\Post;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;

class PostExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function save($postId, $user, $input)
    {
        $out = collect();
        self::post_valid(intval($postId), $input, $out);
        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text'];

        if(self::$result['success'])
        {
            PostManager::edit($out['post'], $out['text'], $out['check'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function post_valid($postId, $input, $out)
    {
        $post = Post::find(intval($postId));
        if(is_null($post)) return self::$result['message'] = 'Пост не найден';

        if(mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPost($input, $post->topic);

        self::$result['success'] = true;
    }

    public static function premod($postId, $user)
    {
        $out = collect();
        self::premodUnhide_valid(intval($postId), $out);
        if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['user'] = $user;

        if(self::$result['success'])
        {
            PostManager::premod($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
        }

        return self::$result;
    }

    private static function premodUnhide_valid($postId, $out)
    {
        $post = Post::find(intval($postId));
        if(is_null($post)) return self::$result['message'] = 'Пост не найден';
        $out['post'] = $post;
        self::$result['success'] = true;
    }

    public static function unhide($postId, $user)
    {
        $out = collect();
        self::premodUnhide_valid(intval($postId), $out);

        if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['user'] = $user;

        if(self::$result['success'])
        {
            PostManager::unhide($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
        }

        return self::$result;
    }
}
