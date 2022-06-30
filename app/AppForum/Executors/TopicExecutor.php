<?php

namespace App\AppForum\Executors;

use App\Topic;
use App\AppForum\Helpers\IpHelper;
use App\AppForum\Managers\PostManager;
use App\AppForum\Managers\UserManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\ForumManager;
use App\AppForum\Managers\TopicManager;

class TopicExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($topicId, $user, $input)
    {
        $out = collect();
        self::post_valid(intval($topicId), $input, $out);

        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;
        $out['text'] = $input['text'];

        $ip = IpHelper::getIp();

        if (self::$result['success']) {
            $post = PostManager::post($out['topic'], $out['text'], $out['check'], $user, $ip);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['user'] = $user;
            $data = json_decode($out['topic']->DATA, false);
            $data->last_post->user_name = $post->user->name;
            $data->last_post->user_id = $post->user->id;
            $data->last_post->date = $post->datatime;
            $data->inf->post_count++;
            $out['DATA'] = json_encode($data);
            TopicManager::dataedit($out['topic'], $out['DATA']);

            $data = json_decode($post->user->DATA, false);
            $data->post_count++;
            $out['DATA'] = json_encode($data);
            UserManager::dataedit($user, $out['DATA']);

            $data = json_decode($post->topic->forum['DATA'], false);
            $data->inf->post_count++;
            $data->last_post->user_name = $post->user->name;
            $data->last_post->user_id = $post->user->id;
            $data->last_post->title = $post->topic->title;
            $data->last_post->post_id = $post->topic->id;
            $data->last_post->date = $post->datatime;
            $out['DATA'] = json_encode($data);

            ForumManager::dataedit($post->topic->forum, $out['DATA']);
        }

        return self::$result;
    }

    private static function post_valid($topicId, $input, $out)
    {
        $topic = Topic::find(intval($topicId));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        if (mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['topic'] = $topic;
        $out['check'] = CheckedHelper::checkPost($input, $topic);

        self::$result['success'] = true;
    }

    public static function edit($topicId, $user, $input)
    {
        $out = collect();
        self::topicEdit_valid(intval($topicId), $input, $out);

        if (!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;
        $out['title'] = $input['title'];

        if (self::$result['success']) {
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
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        if (mb_strlen($input['title']) > 13000 && !is_null($input['title'])) $out['title'] = mb_strimwidth($input['title'], 0, 100, "...");

        $out['topic'] = $topic;
        $out['check'] = CheckedHelper::checkTopic($input, $topic->forum);

        self::$result['success'] = true;
    }

}
