<?php

namespace App\AppForum\Executors;

use App\Post;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;

class PostExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function save($postId, $user, $input)
    {
        $out = collect();
        self::post_valid($postId, $user, $input, $out);


        if(self::$result['success'])
        {
            PostManager::edit($out['post'], $out['text'], $out['check'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function post_valid($postId, $user, $input, $out)
    {
        if(is_null($user))  return self::$result['message'] = 'Пожалуйста, выполните вход на форум!';
        $post = Post::find(intval($postId));
        if(is_null($post)) return self::$result['message'] = 'Пост не найден';

        if(!isset($input['text']) || empty($input['text'])) return self::$result['message'] = 'Введите текст!';

        //if(strlen($input['text']) > 13000) return self::$result['message'] = 'max dlina posta 13k simvolov';
        if(mb_strlen($input['text']) < 2) return self::$result['message'] = 'Минимальнная длина сообщения 2 символа';
        if(mb_strlen($input['text']) > 13000) $input['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['post'] = $post;
        $out['text'] = $input['text'];
        $out['check'] = CheckedHelper::checkPost($input, $post->topic);

        self::$result['success'] = true;
    }

    public static function premod($postId, $user)
    {
        $out = collect();
        self::premodUnhide_valid($postId, $user, $out);

        if(self::$result['success'])
        {
            PostManager::premod($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
        }

        return self::$result;
    }

    private static function premodUnhide_valid($postId, $user, $out)
    {
        if(is_null($user))  return self::$result['message'] = 'Пожалуйста, выполните вход на форум!';
        $post = Post::find($postId);
        if(is_null($post)) return self::$result['message'] = 'Пост не найден';

        $out['post'] = $post;
        $out['user'] = $user;

        self::$result['success'] = true;
    }

    public static function unhide($postId, $user)
    {
        $out = collect();
        self::premodUnhide_valid($postId, $user, $out);

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
