<?php

namespace App\AppForum\Executors;

use App\Forum;
use App\AppForum\Helpers\IpHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\PostManager;
use App\AppForum\Managers\UserManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\ForumManager;
use App\AppForum\Managers\TopicManager;
use App\AppForum\Executors\BaseExecutor;
use Symfony\Component\Console\Input\Input;

class ForumExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($forumId, $user, $input)
    {
        $out = collect();

        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text']; $out['title'] = $input['title'];

        if(self::$result['success']) self::topic_valid(intval($forumId), $input, $out, $user);

        $ip = IpHelper::getIp();
        if(self::$result['success'])
        {
            $topic = TopicManager::post($out['forum'], $out['title'], $out['check'], $user);
            self::$result['topicId'] = $topic->id;
            self::$result['title_slug'] = ForumHelper::slugify($topic->title);
            $out['check'] = CheckedHelper::checkPostTopic($input, $topic);
            $post = PostManager::post($topic, $out['text'], $out['check'], $user, $ip);

            $data = json_decode($post->topic->DATA, false);
            $data->last_post->user_name = $post->user->name;
            $data->last_post->user_id = $post->user->id;
            $data->last_post->title = $post->topic->title;
            $data->last_post->post_id = $post->topic->id;
            $data->last_post->date = $post->datetime;
            $out['DATA'] = json_encode($data);
            TopicManager::dataedit($topic, $out['DATA']);

            $data = json_decode($post->user->DATA, false);
            $data->post_count++;
            $out['DATA'] = json_encode($data);
            UserManager::dataedit($user, $out['DATA']);

            $data = json_decode($post->topic->forum['DATA'], false);
            $data->inf->topic_count++;
            $data->last_post->user_name = $post->user->name;
            $data->last_post->user_id = $post->user->id;
            $data->last_post->title = $post->topic->title;
            $data->last_post->post_id = $post->topic->id;
            $data->last_post->date = $post->datetime;
            $out['DATA'] = json_encode($data);

            ForumManager::dataedit($post->topic->forum, $out['DATA']);

            self::$result['message'] = 'OK';
        }
        return self::$result;
    }

    private static function topic_valid($forumId, $input, $out, $user)
    {
        self::$result['success'] = false;
        $forum = Forum::find(intval($forumId));
        if(is_null($forum)) return self::$result['message'] = 'Раздел с темами не найден';

        if (ModerHelper::banForum($user, $forum)) return self::$result['message'] = 'Пользователь заблокирован на данном форуме';

        $user_role = ModerHelper::user_role($user);
        if (!ModerHelper::visForum($user_role, $forum->id, $forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для публикаций на данном форуме';

        if($forum->block && !ModerHelper::moderPost($user_role, $forum->id, $forum->section_id, $user, $forum->topic_id)) return self::$result['message'] = 'Отсутвует доступ для публикаций на данном форуме';

        if(mb_strlen($input['text']) > 13000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 13000, "...");

        $out['forum'] = $forum;
        $out['check'] = CheckedHelper::checkTopic($input, $forum);
        self::$result['success'] = true;
    }

    public static function forum ($forumId, $user)
    {
        self::$result['success'] = false;

        if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;

        if(self::$result['success']) self::forum_valid($forumId, $user);

        if(self::$result['success']) self::$result['message'] = 'OK';
        return self::$result;
    }

    private static function forum_valid($forumId, $user)
    {
        $forum = Forum::find(intval($forumId));
        if(is_null($forum)) return self::$result['message'] = 'Раздел с темами не найден';

        if (ModerHelper::banForum($user, $forum)) return self::$result['message'] = 'Пользователь заблокирован на данном форуме';

        self::$result['success'] = true;
    }

}
