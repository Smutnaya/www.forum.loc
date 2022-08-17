<?php

namespace App\AppForum\Executors;

use App\Like;
use App\Post;
use App\User;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Managers\LikeManager;
use App\AppForum\Managers\PostManager;
use App\AppForum\Managers\UserManager;

class LikeExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function like($postId, $user, $page)
    {
        $out = collect();
        $out['action'] = null;
        self::likes_valid(intval($postId), $user, $out);

        if (self::$result['success']) {
            $out['action_new'] = 'like';
            $data = json_decode($out['post']['DATA'], false);
            $user_data = json_decode($out['user']['DATA'], false);

            if ($out['action'] == null) {
                LikeManager::new($out['post'], $user, $out['action_new']);
                $data->like++;
                $user_data->like++;
            } elseif ($out['action'] == 'dislike' && $user->id == $out['like_user']['id'] && !is_null($out['like_user']['id'])) {
                LikeManager::updata($out['like'], $out['action_new']);
                $data->like++;
                $data->dislike--;
                if ($data->dislike < 0) $data->dislike = 0;
                $user_data->like += 2;
            }
            $data->dislike_name = self::dislike_name($out['post']['id']);
            $data->like_name = self::like_name($out['post']['id']);
            $out['data'] = json_encode($data);
            $out['user_data'] = json_encode($user_data);

            PostManager::updata($out['post'], $out['data']);
            UserManager::dataedit($out['user'], $out['user_data']);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']['topic_id'], $out['user']['role_id']);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    public static function likem($postId, $user, $page)
    {
        $out = collect();
        $out['action'] = null;
        self::likes_valid(intval($postId), $user, $out);
        if (self::$result['success']) {
            $data = json_decode($out['post']['DATA'], false);
            $user_data = json_decode($out['user']['DATA'], false);

            if ($out['action'] == 'like' && $user->id == $out['like_user']['id'] && !is_null($out['like_user']['id'])) {
                LikeManager::del($out['like']);
                $data->like--;
                if ($data->like < 0) $data->like = 0;
                $user_data->like--;
            }
            $data->like_name = self::like_name($out['post']['id']);
            $out['data'] = json_encode($data);
            $out['user_data'] = json_encode($user_data);

            PostManager::updata($out['post'], $out['data']);
            UserManager::dataedit($out['user'], $out['user_data']);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']['topic_id'], $out['user']['role_id']);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    public static function dislike($postId, $user, $page)
    {
        $out = collect();
        $out['action'] = null;
        self::likes_valid(intval($postId), $user, $out);

        if (self::$result['success']) {
            $out['action_new'] = 'dislike';
            $data = json_decode($out['post']['DATA'], false);
            $user_data = json_decode($out['user']['DATA'], false);

            if ($out['action'] == null) {
                LikeManager::new($out['post'], $user, $out['action_new']);
                $data->dislike++;
                $user_data->like--;
            } elseif ($out['action'] == 'like' && $user->id == $out['like_user']['id'] && !is_null($out['like_user']['id'])) {
                LikeManager::updata($out['like'], $out['action_new']);
                $data->dislike++;
                $data->like--;
                if ($data->like < 0) $data->like = 0;
                $user_data->like -= 2;
            }
            $data->like_name = self::like_name($out['post']['id']);
            $data->dislike_name = self::dislike_name($out['post']['id']);
            $out['data'] = json_encode($data);
            $out['user_data'] = json_encode($user_data);

            PostManager::updata($out['post'], $out['data']);
            UserManager::dataedit($out['user'], $out['user_data']);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']['topic_id'], $out['user']['role_id']);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    public static function dislikem($postId, $user, $page)
    {
        $out = collect();
        $out['action'] = null;
        self::likes_valid(intval($postId), $user, $out);

        if (self::$result['success']) {
            $data = json_decode($out['post']['DATA'], false);
            $user_data = json_decode($out['user']['DATA'], false);

            if ($out['action'] == 'dislike' && $user->id == $out['like_user']['id'] && !is_null($out['like_user']['id'])) {
                LikeManager::del($out['like']);
                $data->dislike--;
                if ($data->dislike < 0) $data->dislike = 0;
                $user_data->like++;
            }
            $data->dislike_name = self::dislike_name($out['post']['id']);
            $out['data'] = json_encode($data);
            $out['user_data'] = json_encode($user_data);

            PostManager::updata($out['post'], $out['data']);
            UserManager::dataedit($out['user'], $out['user_data']);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $user;
            $topicPage = ForumHelper::topicPage($out['post']['topic_id'], $out['user']['role_id']);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    private static function likes_valid($postId, $user, $out)
    {
        $post = Post::find(intval($postId));
        if(is_null($post)) return self::$result['message'] = 'Пост не найден';
        if(is_null($user)) return self::$result['message'] = 'Выполните вход на сайт';

        $out['post'] = $post;
        $out['user'] = $post->user;

        $like = Like::where([['post_id', $post->id], ['user_id', $user->id]])->first();
        if (!is_null($like))
        {
            $out['action'] = $like->action;
            $out['like_user'] = User::find(intval($like->user_id));
        }
        $out['like'] = $like;


        self::$result['success'] = true;
    }

    public static function like_name($postId)
    {
        $like_name = null;
        $likes = Like::where([['post_id', intval($postId)], ['action', 'like']])->get();
        if (!is_null($likes)) {
            $arrayLength = count($likes);
            $counter = 0;
            foreach ($likes as $like) {
                $like_name .= $like->user->name;
                if (++$counter != $arrayLength) $like_name .= ', ';
            }
        }
        return $like_name;
    }
    public static function dislike_name($postId)
    {
        $dislike_name = null;
        $dislikes = Like::where([['post_id', intval($postId)], ['action', 'dislike']])->get();
        if (!is_null($dislikes)) {
            $arrayLength = count($dislikes);
            $counter = 0;
            foreach ($dislikes as $dislike) {
                $dislike_name .= $dislike->user->name;
                if (++$counter != $arrayLength) $dislike_name .= ', ';
            }
        }
        return $dislike_name;
    }
}
