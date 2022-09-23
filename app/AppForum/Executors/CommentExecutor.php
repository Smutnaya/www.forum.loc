<?php

namespace App\AppForum\Executors;

use App\Topic;
use App\Comment;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\TopicManager;
use App\AppForum\Managers\CommentManager;

class CommentExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function del($comment_id, $user)
    {
        $out = collect();

        $user_role = ModerHelper::user_role($user);
        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::del_valid(intval($comment_id), $out, $user,  $user_role);
        $out['user'] = $user;

        if (self::$result['success']) {
            $topic_id = $out['comment']['topic_id'];
            CommentManager::del($out['comment']);

            $topic = Topic::find(intval($topic_id));
            $out['topic'] = $topic;

            $data = json_decode($topic->DATA, false);
            $data->inf->comment--;
            if ($data->inf->comment < 0) $data->inf->comment = 0;
            $out['DATA'] = json_encode($data);
            TopicManager::dataedit($out['topic'], $out['DATA']);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topic->id;
            self::$result['user'] = $out['user'];
        }

        return self::$result;
    }

    public static function del_valid($comment_id, $out, $user,  $user_role)
    {
        self::$result = ['success' => false];
        $comment = Comment::find(intval($comment_id));
        if (is_null($comment)) return self::$result['message'] = 'Комментарий не найден';
        $out['comment'] = $comment;

        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }

        if (is_null($user_role) && $newspaper != $comment->forum_id && $comment->user_id != $user->id) return self::$result['message'] = 'Отсутсвуют права для удаления комментария';
        if ($user_role < 8  && $user_role != 4 && $newspaper != $comment->forum_id && $comment->user_id != $user->id) return self::$result['message'] = 'Отсутсвуют права для удаления комментария';

        self::$result['success'] = true;
    }
}
