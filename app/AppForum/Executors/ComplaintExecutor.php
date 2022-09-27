<?php

namespace App\AppForum\Executors;

use App\Post;
use App\Complaint;
use App\AppForum\Helpers\IpHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Managers\ComplaintManager;

class ComplaintExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function request($post_id, $user, $page)
    {
        $out = collect();
        if (!is_null(BaseExecutor::user_valid($user))) {
            self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        } else {
            self::$result['success'] = true;
            $out['user'] = $user;
        }

        if (self::$result['success']) {
            if (!is_null(BaseExecutor::action_time_valid($user))) {
                self::$result = ['success' => false, 'message' => BaseExecutor::action_time_valid($user)];
            } else self::$result['success'] = true;
        }

        if (self::$result['success']) self::request_valid(intval($post_id), $user, $out);

        if (self::$result['success']) {
            ComplaintManager::request($out);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']['topic_id'], $out['user']['role_id']);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }
        return self::$result;
    }
    private static function request_valid($post_id, $user, $out)
    {
        self::$result['success'] = false;
        $post = Post::find(intval($post_id));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';
        $complaints = Complaint::where([['post_id', $post->id], ['user_id', $user->id], ['close', false]])->get();
        if ($complaints->count() > 0) return self::$result['message'] = 'Жалоба на данное сообщение уже подана';
        $out['post'] = $post;

        $out['ip'] = IpHelper::getIp();

        self::$result['success'] = true;
    }

    public static function ok($complaint_id, $user)
    {
        $out = collect();
        if (!is_null(BaseExecutor::user_valid($user))) {
            self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        } else {
            self::$result['success'] = true;
            $out['user'] = $user;
        }
        if (self::$result['success']) self::close_valid(intval($complaint_id), $user, $out);

        if (self::$result['success']) {
            $DATA = json_decode($out['complaint']['DATA'], false);
            $DATA->approval = true;
            $DATA->close_date = date('d.m.Y H:i', time());
            $DATA->user_id_close = $user->id;
            $DATA->user_name_close = $user->name;
            $DATA->close_datetime = time();
            $out['DATA'] = json_encode($DATA);
            ComplaintManager::close($out['complaint'], $out['close'] = true, $out['DATA']);
            self::$result['message'] = 'Жалоба одобрена';
        }
        return self::$result;
    }
    public static function no($complaint_id, $user)
    {
        $out = collect();
        if (!is_null(BaseExecutor::user_valid($user))) {
            self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        } else {
            self::$result['success'] = true;
            $out['user'] = $user;
        }
        if (self::$result['success']) self::close_valid(intval($complaint_id), $user, $out);

        if (self::$result['success']) {
            $DATA = json_decode($out['complaint']['DATA'], false);
            $DATA->approval = false;
            $DATA->close_date = date('d.m.Y H:i', time());
            $DATA->user_id_close = $user->id;
            $DATA->user_name_close = $user->name;
            $DATA->close_datetime = time();
            $out['DATA'] = json_encode($DATA);
            ComplaintManager::close($out['complaint'], $out['close'] = true, $out['DATA']);
            self::$result['message'] = 'Жалоба отклонена';
        }
        return self::$result;
    }

    private static function close_valid($complaint_id, $user, $out)
    {
        self::$result['success'] = false;
        $complaint = Complaint::find(intval($complaint_id));
        if (is_null($complaint)) return self::$result['message'] = 'Данные о жлобе не найдены';
        if ($complaint->close) return self::$result['message'] = 'Жалоба была рассмотрена ранее';
        $out['complaint'] = $complaint;
        $out['close'] = true;
        self::$result['success'] = true;
    }
}
