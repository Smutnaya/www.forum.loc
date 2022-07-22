<?php

namespace App\AppForum\Executors;

use App\Post;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Helpers\ForumHelper;

class PostExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function save($postId, $user, $input, $page)
    {
        $out = collect();
        self::post_valid(intval($postId), $user, $input, $out);
        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;
        $out['text'] = $input['text'];

        if (self::$result['success']) {
            PostManager::edit($out['post'], $out['text'], $out['check'], $out['data'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']->topic_id);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }
    private static function post_valid($postId, $user, $input, $out)
    {
        $post = Post::find(intval($postId));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';

        if (mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPost($input, $post->topic);

        $data = json_decode($post->DATA, false);
        $data->user_name_edit = $user->name;
        $data->date_edit = time();
        $data->first_edit = $post->text;
        $out['data'] = json_encode($data);

        self::$result['success'] = true;
    }

    public static function save_moder($postId, $user, $input, $page)
    {
        $out = collect();
        self::post_valid_moder(intval($postId), $user, $input, $out);
        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;
        $out['text'] = $input['text'];

        if (self::$result['success']) {
            PostManager::edit($out['post'], $out['text'], $out['check'], $out['data'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']->topic_id);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }
    private static function post_valid_moder($postId, $user, $input, $out)
    {
        $post = Post::find(intval($postId));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';

        if (mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPost($input, $post->topic);

        $data = json_decode($post->DATA, false);
        $data->user_name_moder = $user->name;
        $data->date_moder = time();
        $data->first = $post->text;
        $out['data'] = json_encode($data);

        self::$result['success'] = true;
    }

    public static function premod($postId, $user, $page)
    {
        $out = collect();
        self::premodUnhide_valid(intval($postId), $out);
        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;
        $out['user'] = $user;

        if (self::$result['success']) {
            PostManager::premod($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
            $topicPage = ForumHelper::topicPage($out['post']->topic_id);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    private static function premodUnhide_valid($postId, $out)
    {
        $post = Post::find(intval($postId));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';
        $out['post'] = $post;
        self::$result['success'] = true;
    }

    public static function unhide($postId, $user, $page)
    {
        $out = collect();
        self::premodUnhide_valid(intval($postId), $out);

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;
        $out['user'] = $user;

        if (self::$result['success']) {
            PostManager::unhide($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
            $topicPage = ForumHelper::topicPage($out['post']->topic_id);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }
}
