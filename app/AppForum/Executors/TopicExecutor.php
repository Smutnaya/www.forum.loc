<?php

namespace App\AppForum\Executors;

use App\Post;
use App\View;
use App\Forum;
use App\Topic;
use App\Images;
use App\Other_role;
use App\AppForum\Helpers\IpHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\PostManager;
use App\AppForum\Managers\UserManager;
use App\AppForum\Managers\ViewManager;
use App\AppForum\Helpers\CheckedHelper;
use App\AppForum\Managers\ForumManager;
use App\AppForum\Managers\TopicManager;
use App\AppForum\Executors\BaseExecutor;
use App\AppForum\Executors\PostExecutor;
use App\AppForum\Managers\ImagesManager;
use App\AppForum\Managers\CommentManager;
use App\AppForum\Helpers\ClanAllianceHelper;

class TopicExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($topicId, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) {
            if (!is_null(BaseExecutor::action_time_valid($user))) {
                self::$result = ['success' => false, 'message' => BaseExecutor::action_time_valid($user)];
            } else self::$result['success'] = true;
        }

        if (self::$result['success']) self::post_valid(intval($topicId), $input, $out, $user);

        $out['text'] = $input['text'];
        $ip = IpHelper::getIp();

        if (self::$result['success']) {
            $post = PostManager::post($out['topic'], $out['text'], $out['check'], $user, $ip);

            self::images_valid($post, $out, $user);

            if (!is_null($out['images'])) {
                foreach ($out['images'] as $image) {
                    if (!is_null(stristr($post->text, $image->url))) {
                        ImagesManager::post_id($image, $post->id);
                    }
                }
            }

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['title_slug'] = ForumHelper::slugify($out['topic']->title);
            self::$result['user'] = $user;

            $out['last_post'] = PostExecutor::last_post($out['topic']['id']);
            TopicManager::lastPostEdit($out['topic'], $out['last_post']);

            $data = json_decode($out['topic']->DATA, false);
            $data->last_post->user_name = $post->user->name;
            $data->last_post->user_id = $post->user->id;
            $data->last_post->avatar = $post->user->avatar;
            $data->last_post->title = $post->topic->title;
            $data->last_post->post_id = $post->topic->id;
            $data->last_post->date = $post->datetime;
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
            $data->last_post->avatar = $post->user->avatar;
            $data->last_post->title = $post->topic->title;
            $data->last_post->post_id = $post->topic->id;
            $data->last_post->date = $post->datetime;
            $out['DATA'] = json_encode($data);
            ForumManager::dataedit($post->topic->forum, $out['DATA']);
            UserManager::actionTimeEdit($user);
        }
        return self::$result;
    }

    private static function post_valid($topicId, $input, $out, $user)
    {
        self::$result = ['success' => false];
        $topic = Topic::find(intval($topicId));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';


        $user_role = ModerHelper::user_role($user);

        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }

        $other_roles = Other_role::where('user_id', $user->id)->get();
        $moder = false;
        if (!is_null($other_roles)) {
            foreach ($other_roles as $other_role)
                if ($other_role->moderation) $moder = true;
        }

        if ($topic->forum_id == 42 && $user_role < 6 && count($topic->posts) > 0 && !$moder) return self::$result['message'] = 'Дождитесь ответа модератора';
        if ($newspaper != $topic->forum_id && !ModerHelper::visForum($user_role, $topic->forum_id, $topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для публикаций на данном форуме';
        if ($topic->private && !ClanAllianceHelper::userAllianceModer($user, $topic->forum) && !ClanAllianceHelper::userClanModer($user, $topic->forum)) return self::$result['message'] = 'Отсутвует доступ для публикаций на данном форуме';

        if (ModerHelper::banTopic($user, $topic)) return self::$result['message'] = 'Пользователь заблокирован в теме';

        if (mb_strlen($input['text']) > 50000 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 50000, "...");

        if ($topic->block && !ModerHelper::moderPost($user_role, $topic->forum_id, $topic->forum->section_id, $user, $topic->id) && !ClanAllianceHelper::userAllianceModer($user, $topic->forum) && !ClanAllianceHelper::userClanModer($user, $topic->forum)) return self::$result['message'] = 'Тема закрыта для новых публикаций';

        $out['topic'] = $topic;
        $out['check'] = CheckedHelper::checkPost($input, $topic);
        self::$result['success'] = true;
    }

    private static function images_valid($post, $out, $user)
    {
        $out['images'] = null;
        $images = Images::where([['user_id', $user->id], ['datetime', '>=', strtotime('-12 hours')], ['post_id', null]])->get();
        if ($images->count() > 0) return $out['images'] = $images;
    }

    public static function edit($topicId, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::topicEdit_valid(intval($topicId), $input, $out, $user);

        $out['title'] = $input['title'];

        if (self::$result['success']) {
            TopicManager::edit($out['topic'], $out['title'], $out['check'], $out['forum_data'], $user);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['title_slug'] = ForumHelper::slugify($out['topic']->title);
            self::$result['user'] = $user;
        }

        return self::$result;
    }

    private static function topicEdit_valid($topicId, $input, $out, $user)
    {
        $topic = Topic::find(intval($topicId));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        if (mb_strlen($input['title']) > 200 && !is_null($input['title'])) $out['title'] = mb_strimwidth($input['title'], 0, 200, "...");
        $user_role = ModerHelper::user_role($user);
        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }
        if ($newspaper != $topic->forum_id && !(ModerHelper::moderTopicEdit($user->role_id, $user->id, $topic->datetime, json_decode($topic->DATA, false), $topic->user_id, $topic->forum_id, $topic->forum->section_id, $topic->id))) return self::$result['message'] = 'Отсутсвуют права для редактирования темы';
        $user_role = ModerHelper::user_role($user);
        if ($newspaper != $topic->forum_id && !ModerHelper::visForum($user_role, $topic->forum_id, $topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутсвуют права для редактирования темы';

        $forum_data = json_decode($topic->DATA, false);
        if ($user->id != $topic->user_id) $forum_data->moder = time();
        $out['forum_data'] = json_encode($forum_data);
        $out['topic'] = $topic;
        $out['check'] = CheckedHelper::checkTopicEdit($input);

        self::$result['success'] = true;
    }

    public static function move($topicId, $user, $input)
    {
        $out = collect();
        self::topicMove_valid(intval($topicId), $input, $out, $user);

        if (self::$result['success']) {
            $forum_id = $out['topic']->forum_id;
            $out['forum_id_from'] = $forum_id;
            $out['forum_id_in'] = $out['forum_id'];
            TopicManager::move($out['topic'], $out['forum_id']);
            PostManager::move($out['topic']->id, $out['forum_id']);
            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['title_slug'] = ForumHelper::slugify($out['topic']->title);
            self::$result['user'] = $user;
            if ($out['forum_id_from'] != $out['forum_id_in']) {
                self::DATAMove_valid($out['forum_id_from'], $out['forum_id_in'], $out);
                ForumManager::dataedit($out['forum_from'], $out['dafa_from']);
                ForumManager::dataedit($out['forum_in'], $out['dafa_in']);
            }
        }
        return self::$result;
    }
    private static function topicMove_valid($topicId, $input, $out, $user)
    {
        $topic = Topic::find(intval($topicId));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';
        if (empty($input['check'])) return self::$result['message'] = 'Не выбран путь для перемещения';

        $forum = Forum::find(intval($input['check']['0']));
        if (is_null($forum)) return self::$result['message'] = 'Путь для перемещения не найден';

        if ($topic->forum->section_id == 7 && $forum->section_id != 7 && $user->role_id < 11) {
            return self::$result['message'] = 'Информацию из служебного форума нельзя перемещать в Общий';
        } else {
            if (!(ModerHelper::moderTopicMove($user->role_id, $topic->forum_id, $topic->forum->section_id, $user, $topic->id))) return self::$result['message'] = 'Отсутсвуют права для перемещения темы';
            if (!(ModerHelper::moderTopicMoveTo($user->role_id, $forum->id, $forum->section_id, $user, $topic->id))) return self::$result['message'] = 'Отсутсвуют права для перемещения темы';
        }

        $out['topic'] = $topic;
        $out['forum_id'] = intval($input['check']['0']);

        self::$result['success'] = true;
    }

    private static function DATAMove_valid($forum_id_from, $forum_id_in, $out)
    {
        $forum_from = Forum::find(intval($forum_id_from));
        $forum_in = Forum::find(intval($forum_id_in));

        $forum_data_from = json_decode($forum_from->DATA, false);
        $forum_data_in = json_decode($forum_in->DATA, false);

        if ($forum_data_from->inf->topic_count > 0) $forum_data_from->inf->topic_count--;
        $forum_data_in->inf->topic_count++;
        if ($forum_data_from->inf->topic_count < 0) $forum_data_from->inf->topic_count = 0;

        $forum_data_in->inf->post_count += $forum_data_from->inf->post_count;
        if ($forum_data_from->inf->post_count > 0) $forum_data_from->inf->post_count -= $forum_data_from->inf->post_count;
        if ($forum_data_from->inf->post_count < 0) $forum_data_from->inf->post_count = 0;

        $last_post_from = Post::where('forum_id', $forum_from->id)->orderBy('datetime', 'desc')->first();
        $last_post_in = Post::where('forum_id', $forum_in->id)->orderBy('datetime', 'desc')->first();

        $forum_data_from->last_post = self::DATAlastPost_valid($last_post_from, $forum_data_from->last_post);
        $forum_data_in->last_post = self::DATAlastPost_valid($last_post_in, $forum_data_in->last_post);
        $out['forum_from'] = $forum_from;
        $out['forum_in'] = $forum_in;
        $out['dafa_from'] = json_encode($forum_data_from);
        $out['dafa_in'] = json_encode($forum_data_in);
    }
    private static function DATAlastPost_valid($post, $last_post_data)
    {
        if (is_null($post)) {
            $last_post_data->user_name = null;
            $last_post_data->user_id = null;
            $last_post_data->avatar = null;
            $last_post_data->title = null;
            $last_post_data->post_id = null;
            $last_post_data->date = null;
        } else {
            $last_post_data->user_name = $post->user->name;
            $last_post_data->user_id = $post->user->id;
            $last_post_data->avatar = $post->user->avatar;
            $last_post_data->title = $post->topic->title;
            $last_post_data->post_id = $post->topic->id;
            $last_post_data->date = $post->datetime;
        }
        return $last_post_data;
    }

    public static function view($topicId, $user)
    {
        $out = collect();
        $out['viewAdd'] = false;
        self::topicView_valid(intval($topicId), $user, $out);

        if (self::$result['success']) {
            TopicManager::dataedit($out['topic'], $out['DATA']);
            ViewManager::post($out['topic_id'], $out['user_id'], $out['ip']);
        }
        return self::$result;
    }
    private static function topicView_valid($topicId, $user, $out)
    {

        $topic = Topic::find(intval($topicId));
        if (is_null($topic)) {
            return self::$result;
        } else {
            $out['topic_id'] = $topic->id;
            $out['topic'] = $topic;
        }

        if (is_null($user)) {
            $out['user_id'] = null;
        } else {
            $out['user_id'] = $user->id;
        }

        $out['ip'] = IpHelper::getIp();
        $data = json_decode($topic->DATA, false);
        $view_datetime = View::select('datetime')->where([['ip', $out['ip']], ['topic_id', $out['topic_id']]])->orderBy('datetime', 'desc')->first();

        if (is_null($view_datetime)) {
            $data->inf->views++;
            self::$result['success'] = true;
        } elseif ($view_datetime->datetime > strtotime('24 hours')) {
            $data->inf->views++;
            self::$result['success'] = true;
        }
        $out['DATA'] = json_encode($data);
    }


    //comments
    public static function comment($topicId, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) {
            if (!is_null(BaseExecutor::action_time_valid($user))) {
                self::$result = ['success' => false, 'message' => BaseExecutor::action_time_valid($user)];
            } else self::$result['success'] = true;
        }

        if (self::$result['success']) self::comment_valid(intval($topicId), $input, $out, $user);

        $out['text'] = $input['text'];
        $ip = IpHelper::getIp();

        if (self::$result['success']) {
            $comment = CommentManager::post($out['topic']['id'], $out['text'], $user->id, $ip);

            self::$result['message'] = 'OK';
            self::$result['topicId'] = $topicId;
            self::$result['title_slug'] = ForumHelper::slugify($out['topic']->title);
            self::$result['user'] = $user;

            $data = json_decode($out['topic']->DATA, false);
            $data->inf->comment++;
            $out['DATA'] = json_encode($data);
            TopicManager::dataedit($out['topic'], $out['DATA']);

            $data = json_decode($comment->user->DATA, false);
            $data->post_count++;
            $out['DATA'] = json_encode($data);
            UserManager::dataedit($user, $out['DATA']);
            UserManager::actionTimeEdit($user);
        }
        return self::$result;
    }

    private static function comment_valid($topicId, $input, $out, $user)
    {
        self::$result = ['success' => false];
        $topic = Topic::find(intval($topicId));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';

        $user_role = ModerHelper::user_role($user);
        if (is_null($user->newspaper_id)) {
            $newspaper = 0;
        } else {
            $newspaper = $user->newspaper->forum_id;
        }

        if ($newspaper != $topic->forum_id && !ModerHelper::visForum($user_role, $topic->forum_id, $topic->forum->section_id, $user)) return self::$result['message'] = 'Отсутвует доступ для публикаций на данном форуме';
        if ($newspaper != $topic->forum_id && ModerHelper::banTopic($user, $topic)) return self::$result['message'] = 'Пользователь заблокирован в теме';
        if (mb_strlen($input['text']) > 7500 && !is_null($input['text'])) $out['text'] = mb_strimwidth($input['text'], 0, 7500, "...");
        if ($topic->block && !ModerHelper::moderPost($user_role, $topic->forum_id, $topic->forum->section_id, $user, $topic->id)) return self::$result['message'] = 'Тема закрыта для комментариев';

        $out['topic'] = $topic;
        self::$result['success'] = true;
    }
}
