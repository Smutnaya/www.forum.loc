<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Message;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ComplaintHelper;
use App\AppForum\Managers\MessageManager;

class MessageViewer
{
    private static function init()
    {
        return collect([
            'sectionsAside' => collect(), // id, title, description
            'user' => null,
            'message_new' => null,
            'messages' => collect(),
            'message' => null,
            'title' => null,
            'to' => null,
            'complaints' => collect(),

            'pagination' => collect([
                'page' => null,
                'pages' => null,
                'user_id' => null,
            ]),
        ]);
    }

    public static function index($user, $page)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (is_null($user)) return $model;
        $model['user'] = $user;
        $model['complaints'] = ComplaintHelper::review();
        $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
        $model['message_new'] = $mes->count();

        //$messages = Message::where('user_id', $user->id)->get();
        //$messages = Message::all();

        $messagePage = ForumHelper::messagePage($user->id);
        $pages = $messagePage['pages'];
        $take = $messagePage['take'];
        $page = ForumHelper::parsePage($page, $pages);
        $skip = ($page - 1) * $take;

        $model['pagination']['user_id'] = $user->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        $messages = self::getMessage($user, $skip, $take);
        if ($messages->count() < 1) return $model;
        self::setMessage($model, $messages);

        return $model;
    }

    public static function getMessage($user, $skip = null, $take = null)
    {
        $messages = collect();

        if (!is_null($skip)) {
            //$messages = Message::where('user_id', $user->id)->orWhere('user_id_to',  $user->id)->orderByDesc('view')->orderByDesc('datetime')->skip($skip)->take($take)->get();
            $messages = Message::where([['user_id_to',  $user->id], ['hide', false]])->orderBy('view')->orderByDesc('datetime')->skip($skip)->take($take)->get();
        } else {
            $messages = Message::where([['user_id_to',  $user->id], ['hide', false]])->orderBy('view')->orderByDesc('datetime')->get();
        }

        return $messages;
    }

    private static function setMessage($model, $messages)
    {
        foreach ($messages as $message) {
            $model['messages']->push([
                'id' => $message->id,
                'title' => $message->title,
                'text' => $message->text,
                'datetime' => ForumHelper::timeFormat($message->datetime),
                'view' => $message->view,
                'user' => $message->user,
                'user_to' => $message->user_to,
            ]);
        }
    }

    public static function new_message($user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (is_null($user)) return $model;
        $model['user'] = $user;
        $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
        $model['message_new'] = $mes->count();

        return $model;
    }

    public static function user_message($user, $post_id)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (is_null($user)) return $model;
        $model['user'] = $user;
        $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
        $model['message_new'] = $mes->count();

        $post = Post::find(intval($post_id));
        if (!is_null($post)) {
            $model['title'] = 'т: ' . $post->topic->title;
            $model['to'] = $post->user->name;
        }

        return $model;
    }

    public static function history($user, $message_id)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (is_null($user)) return $model;
        $model['user'] = $user;
        $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
        $model['message_new'] = $mes->count();

        $message = Message::find(intval($message_id));
        if (is_null($message)) return $model;
        self::setHistory($model, $message);

        MessageManager::view($message);

        return $model;
    }

    private static function setHistory($model, $message)
    {
        $model['message'] = [
            'id' => $message->id,
            'title' => $message->title,
            'text' => $message->text,
            'datetime' => ForumHelper::timeFormat($message->datetime),
            'date' => $message->datetime,
            'view' => $message->view,
            'user' => $message->user,
            'user_to' => $message->user_to,
        ];
    }
}
