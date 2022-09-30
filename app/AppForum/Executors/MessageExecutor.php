<?php

namespace App\AppForum\Executors;

use App\User;
use App\Message;
use App\AppForum\Managers\UserManager;
use App\AppForum\Managers\MessageManager;

class MessageExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function save_message($user, $input)
    {
        $out = collect();

        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text']; $out['title'] = $input['title'];

        if (self::$result['success']) {
            if (!is_null(BaseExecutor::action_time_valid($user))) {
                self::$result = ['success' => false, 'message' => BaseExecutor::action_time_valid($user)];
            } else self::$result['success'] = true;
        }

        if (self::$result['success']) self::save_message_valid($input, $out, $user);

        if (self::$result['success']) {
            MessageManager::new_message($out);
            UserManager::actionTimeEdit($user);
        }
        return self::$result;
    }
    private static function save_message_valid($input, $out, $user)
    {
        self::$result = ['success' => false];

        if ($user->ban_message) return self::$result['message'] = 'Отправка сообщений не доступна, обратитесь к Администрации';
        $out['user'] = $user;

        if(is_null($input['to'])) return self::$result['message'] = 'Введите адресата сообщения';

        $user_to = User::where('name', $input['to'])->first();
        if (is_null($user_to)) return self::$result['message'] = 'Адресат не найден';
        $out['user_to'] = $user_to;

        if(mb_strlen($out['text']) > 50000) $out['text'] = mb_strimwidth($out['text'], 0, 50000, "...");

        self::$result = ['success' => true, 'message' => 'Сообщение '. $user_to->name . ' отправлено'];
    }

    public static function reply($user, $message_id, $input)
    {
        $out = collect();

        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text'];

        if (self::$result['success']) self::reply_valid($input, $out, intval($message_id), $user);

        if (self::$result['success']) {
            MessageManager::new_message($out);
            self::$result['message_id'] = intval($message_id);
        }
        return self::$result;
    }
    private static function reply_valid($input, $out, $message_id, $user)
    {
        self::$result = ['success' => false];

        if ($user->ban_message) return self::$result['message'] = 'Отправка сообщений не доступна, обратитесь к Администрации';
        $out['user'] = $user;

        $message = Message::find(intval($message_id));
        if (is_null($message)) return self::$result['message'] = 'Тема для ответа не найдена';
        $out['title'] = $message->title;

        $user_to = User::find($message->user_id);
        if (is_null($user_to)) return self::$result['message'] = 'Адресат не найден';
        $out['user_to'] = $user_to;

        if(mb_strlen($out['text']) > 50000) $out['text'] = mb_strimwidth($out['text'], 0, 50000, "...");

        self::$result = ['success' => true, 'message' => 'Ответ '. $user_to->name . ' отправлен'];
    }

    public static function hide($user, $message_id, $input)
    {
        $out = collect();

        if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;

        if (self::$result['success']) self::hide_valid($input, $out, intval($message_id), $user);

        if (self::$result['success']) {
            MessageManager::hide($out['message']);
        }
        return self::$result;
    }
    private static function hide_valid($input, $out, $message_id, $user)
    {
        self::$result = ['success' => false];

        $message = Message::find(intval($message_id));
        if (is_null($message)) return self::$result['message'] = 'Сообщение не найдено';
        if($user->id != $message->user_id && $user->id != $message->user_id_to) return self::$result['message'] = 'Сообщение не найдено';
        $out['message'] = $message;

        self::$result = ['success' => true, 'message' => 'Сообщение удалено'];
    }
}
