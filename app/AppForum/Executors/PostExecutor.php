<?php

namespace App\AppForum\Executors;

use App\Post;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;

class PostExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function save($postId, $user, $input, $page)
    {
        $out = collect();

        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::post_valid(intval($postId), $user, $input, $out);
        $out['text'] = $input['text'];
        $user_role = ModerHelper::user_role($user);

        if (self::$result['success']) {
            PostManager::edit($out['post'], $out['text'], $out['check'], $out['data'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']->topic_id, $user_role);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }
    private static function post_valid($postId, $user, $input, $out)
    {
        self::$result = ['success' => false];
        $post = Post::find(intval($postId));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';

        $user_role = ModerHelper::user_role($user);
        if (!ModerHelper::visForum($user_role, $post->topic->forum_id, $post->topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для редактирования тем на данном форуме';

        if (mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPostEdit($input, $post->topic);

        $data = json_decode($post->DATA, false);
        $data->user_name_edit = $user->name;
        $data->date_edit = time();
        $data->first_edit = $post->text;
        $out['data'] = json_encode($data);


        if (!(ModerHelper::moderPostEdit($user->role_id, $user, $user->id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum_id, $post->topic->forum->section_id, $post->topic_id))) return self::$result['message'] = 'Отсутсвуют права для редактирования темы';

        self::$result['success'] = true;
    }

    public static function save_moder($postId, $user, $input, $page)
    {
        $out = collect();

        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::post_valid_moder(intval($postId), $user, $input, $out);
        if (self::$result['success']) self::valid_moderation($postId, $user);
        $out['text'] = $input['text'];

        $user_role = ModerHelper::user_role($user);

        if (self::$result['success']) {
            PostManager::edit($out['post'], $out['text'], $out['check'], $out['data'], $user);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']->topic_id, $user_role);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }
    private static function post_valid_moder($postId, $user, $input, $out)
    {
        self::$result = ['success' => false];
        $post = Post::find(intval($postId));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';

        $user_role = ModerHelper::user_role($user);
        if (!ModerHelper::visForum($user_role, $post->topic->forum_id, $post->topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для модерации тем на данном форуме';

        if (mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPostEdit($input, $post->topic);

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

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::premodUnhide_valid(intval($postId), $out);
        $out['user'] = $user;
        $user_role = ModerHelper::user_role($user);

        self::valid_moderation($postId, $user);

        if (self::$result['success']) {
            PostManager::premod($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
            $topicPage = ForumHelper::topicPage($out['post']->topic_id, $user_role);
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

        $user_role = ModerHelper::user_role($user);
        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::premodUnhide_valid(intval($postId), $out);
        $out['user'] = $user;
        self::valid_moderation($postId, $user);

        if (self::$result['success']) {
            PostManager::unhide($out['post'], $out['user']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
            $topicPage = ForumHelper::topicPage($out['post']->topic_id, $user_role);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    public static function valid_moderation($postId, $user)
    {
        $post = Post::find(intval($postId));
        self::$result['success'] = false;
        //$user_role_id, $user_id, $post_datetime, $post_DATA, $post_user_id, $forum_id, $section_id
        if (!(ModerHelper::moderPost($user->role_id, $post->topic->forum_id, $post->topic->forum->section_id, $user, $post->topic_id))) return self::$result['message'] = 'Отсутсвуют права для модерации темы';

        self::$result['success'] = true;
    }
}
