<?php

namespace App\AppForum\Executors;

class BaseExecutor
{
    public static $message = null;
    public static function user_valid($user)
    {
        if (is_null($user)) return self::$message = 'Пожалуйста, выполните вход на форум!';
        return self::$message;
    }

    public static function text_valid($input)
    {
        if (is_null($input)) return self::$message = 'Введите текст!';
        if (mb_strlen($input) < 2) return self::$message =  'Минимальнная длина сообщения 2 символа';
        return self::$message;
    }

    public static function tema_valid($input)
    {
        if (is_null($input)) return self::$message = 'Введите название темы!';
        return self::$message;
    }

    public static function action_time_valid($user)
    {
        if (is_null($user)) return self::$message = 'Пожалуйста, выполните вход на форум!';
        if (!is_null($user->action_time)) {
            if($user->action_time > strtotime('-5 seconds')) return self::$message = 'Пожалуйста, не флудите!';
        }

        return self::$message;
    }
}
