<?php

namespace App\AppForum\Executors;

use App\Post;
use App\Topic;
use App\Images;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\PostManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\TopicManager;
use Illuminate\Support\Facades\Storage;
use App\AppForum\Managers\ImagesManager;

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
            $out['last_post'] = self::last_post($out['post']['topic_id']);
            $out['topic'] = Topic::find($out['post']['topic_id']);

            self::images_valid($out, $user, $out['post']['id'], $out['post']['user_id']);

            if (!is_null($out['images'])) {
                foreach ($out['images'] as $image) {
                    if (!is_null(stristr($out['text'], $image->url))) {
                        ImagesManager::post_id($image, $out['post']['id']);
                    }
                }
            }
            if (!is_null($out['images2'])) {
                foreach ($out['images2'] as $images2) {
                    if (!is_null(stristr($out['text'], $images2->url))) {
                        ImagesManager::post_id($images2, $out['post']['id']);
                    }
                }
            }
            if (!is_null($out['images_del'])) {
                foreach ($out['images_del'] as $images_del) {
                    $url = explode("c/", $images_del->url);
                    if (!stristr($out['text'], $url[1])) {
                        ImagesManager::del($images_del);
                    }
                }
            }

            if (!is_null($out['last_post'])) {
                TopicManager::lastPostEdit($out['topic'], $out['last_post']);
            }
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

        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }

        if ($newspaper != $post->topic->forum_id && !ModerHelper::visForum($user_role, $post->topic->forum_id, $post->topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для редактирования тем на данном форуме';

        if (mb_strlen($input['text']) > 50000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 50000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPostEdit($input, $post->topic, $post);

        $data = json_decode($post->DATA, false);
        $data->user_name_edit = $user->name;
        $data->date_edit = time();
        $data->first_edit = $post->text;
        $out['data'] = json_encode($data);


        if ($newspaper != $post->topic->forum_id && !(ModerHelper::moderPostEdit($user->role_id, $user, $user->id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum_id, $post->topic->forum->section_id, $post->topic_id))) return self::$result['message'] = 'Отсутсвуют права для редактирования темы';

        self::$result['success'] = true;
    }

    private static function images_valid($out, $user, $post_id, $user_id)
    {
        $out['images'] = null;
        $out['images2'] = null;
        $out['images_del'] = null;

        $images = Images::where([['user_id', $user->id], ['datetime', '>=', strtotime('-12 hours')], ['post_id', null]])->get();
        if ($images->count() > 0) $out['images'] = $images;
        $images2 = Images::where([['user_id', $user_id], ['datetime', '>=', strtotime('-12 hours')], ['post_id', null]])->get();
        if ($images2->count() > 0) $out['images2'] = $images2;

        $images_del = Images::where('post_id', intval($post_id))->get();
        if ($images_del->count() > 0) $out['images_del'] = $images_del;
    }

    public static function save_moder($postId, $user, $input, $page)
    {
        $out = collect();

        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::post_valid_moder(intval($postId), $user, $input, $out);
        if (self::$result['success']) self::valid_moderation(intval($postId), $user);
        $out['text'] = $input['text'];

        $user_role = ModerHelper::user_role($user);

        if (self::$result['success']) {

            PostManager::edit($out['post'], $out['text'], $out['check'], $out['data'], $user);

            self::images_valid($out, $user, $out['post']['id'], $out['post']['user_id']);

            if (!is_null($out['images_del'])) {
                foreach ($out['images_del'] as $images_del) {
                    $url = explode("c/", $images_del->url);
                    if (!stristr($out['text'], $url[1])) {
                        ImagesManager::del($images_del);
                    }
                }
            }

            $out['last_post'] = self::last_post($out['post']['topic_id']);
            if (!is_null($out['last_post'])) {
                $out['topic'] = Topic::find($out['post']['topic_id']);
                TopicManager::lastPostEdit($out['topic'], $out['last_post']);
            }

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

        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }
        if ($newspaper != $post->topic->forum_id && !ModerHelper::visForum($user_role, $post->topic->forum_id, $post->topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для модерации тем на данном форуме';

        if (mb_strlen($input['text']) > 50000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 50000, "...");

        $out['post'] = $post;
        $out['check'] = CheckedHelper::checkPostEdit($input, $post->topic, $post);

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
            $out['last_post'] = self::last_post($out['post']['topic_id']);
            $out['topic'] = Topic::find($out['post']['topic_id']);
            $topic = $out['topic'];
            if (!is_null($out['last_post'])) {
                TopicManager::lastPostEdit($out['topic'], $out['last_post']);
            }
            if ($topic->forum->section_id == 6 && $topic->forum_id != 53) {
                $moder = 0;
                TopicManager::premod_topic($topic, $moder);
            }
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
        self::valid_moderation(intval($postId), $user);

        if (self::$result['success']) {
            PostManager::unhide($out['post'], $out['user']);
            $out['last_post'] = self::last_post($out['post']['topic_id']);
            if (!is_null($out['last_post'])) {
                $out['topic'] = Topic::find($out['post']['topic_id']);
                TopicManager::lastPostEdit($out['topic'], $out['last_post']);
            }
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
        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }
        if ($newspaper != $post->topic->forum_id && !(ModerHelper::moderPost($user->role_id, $post->topic->forum_id, $post->topic->forum->section_id, $user, $post->topic_id))) return self::$result['message'] = 'Отсутсвуют права для модерации темы';

        self::$result['success'] = true;
    }

    public static function del($postId, $user, $page)
    {
        $out = collect();

        $user_role = ModerHelper::user_role($user);
        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::del_valid(intval($postId), $out, $user,  $user_role);
        $out['user'] = $user;

        if (self::$result['success']) {
            $topic_id = $out['post']['topic_id'];
            PostManager::del($out['post']);

            if (!is_null($out['images_del'])) {
                foreach ($out['images_del'] as $images_del) {
                    ImagesManager::del($images_del);
                }
            }

            $out['last_post'] = self::last_post(intval($topic_id));
            if (!is_null($out['last_post'])) {
                $out['topic'] = Topic::find(intval($topic_id));
                TopicManager::lastPostEdit($out['topic'], $out['last_post']);
            }
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $out['post']['topic_id'];
            self::$result['user'] = $out['user'];
            $topicPage = ForumHelper::topicPage($out['post']->topic_id, $user_role);
            $pages = $topicPage['pages'];
            self::$result['page'] = ForumHelper::parsePage($page, $pages);
        }

        return self::$result;
    }

    public static function del_valid($postId, $out, $user,  $user_role)
    {
        self::$result = ['success' => false];
        $out['images_del'] = null;

        $post = Post::find(intval($postId));
        if (is_null($post)) return self::$result['message'] = 'Пост не найден';
        $out['post'] = $post;

        $first_post = Post::where('topic_id', $post->topic_id)->first();
        if ($post->id == $first_post->id) return self::$result['message'] = 'Невозможно удалить первый ответ в теме';

        if (is_null($user_role)) return self::$result['message'] = 'Отсутсвуют права для удаления ответа';
        if ($user_role < 11) return self::$result['message'] = 'Отсутсвуют права для удаления ответа';

        $images_del = Images::where('post_id', intval($postId))->get();
        if ($images_del->count() > 0) $out['images_del'] = $images_del;

        self::$result['success'] = true;
    }

    public static function last_post($topic_id)
    {
        $last_post_collect = Post::where('topic_id', intval($topic_id))->orderBy('datetime', 'desc')->limit(1)->get();
        if ($last_post_collect->count() < 1) return null;
        if ($last_post_collect->count() == 1) {
            $last_post = $last_post_collect['0']->datetime;
        }

        return $last_post;
    }
}
