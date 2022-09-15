<?php

namespace App\AppForum\Executors;

use App\User;
use App\AppForum\Managers\MessageManager;

class MessageExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function save_message( $user, $input)
    {
        $out = collect();

        if(!is_null(BaseExecutor::text_valid($input['text']))) self::$result = ['success' => false, 'message' => BaseExecutor::text_valid($input['text'])];
        else if(!is_null(BaseExecutor::tema_valid($input['title']))) self::$result = ['success' => false, 'message' => BaseExecutor::tema_valid($input['title'])];
        else if(!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result['success'] = true;  $out['text'] = $input['text']; $out['title'] = $input['title'];

        if (self::$result['success']) self::save_message_valid($input, $out, $user);

        if (self::$result['success']) {
            MessageManager::new_message($out);
            // self::$result['user_id'] = $out['user']['id'];
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

        if(mb_strlen($out['text']) > 13000) $out['text'] = mb_strimwidth($out['text'], 0, 13000, "...");

        self::$result = ['success' => true, 'message' => 'Сообщение '. $user_to->name . ' отправлено'];
    }
}