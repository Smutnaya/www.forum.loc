<?php

namespace App\AppForum\Executors;

use App\User;
use App\Images;
use App\AppForum\Managers\UserManager;
use Illuminate\Support\Facades\Storage;
use App\AppForum\Managers\ImagesManager;

class ImagesExecutor extends BaseExecutor
{
    public static $result = ['success' => false];

    public static function images_post($user, $url, $size)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];
        $out['user_id'] = $user->id;

        if (self::$result['success']) self::images_post_valid($user, $url, $size, $out);

        if (self::$result['success']) {
            ImagesManager::images_post($out['url'], $out['user_id'], $out['size']);
        }
        return self::$result;
    }
    private static function images_post_valid($user, $url, $size, $out)
    {
        self::$result = ['success' => false];

        if (is_null($url)) return self::$result['message'] = 'Проверьте загружаемый файл';
        $out['url'] = '/public/uploads/'.$url;

        if (is_null($size)) return self::$result['message'] = 'Проверьте загружаемый файл';
        if ($size < 1) return self::$result['message'] = 'Проверьте загружаемый файл';
        $out['size'] = $size;

        //TODO: исправить на 10 метров
        $limit = Images::where([['user_id', $user->id], ['datetime', '>=', strtotime(date('Y-m-d'))]])->sum('size');
        if ($limit > 20 * 1000000) return self::$result['message'] = 'Превышен суточный лимит на загрузку файлов (20 МБ)';

        self::$result = ['success' => true];
    }

    public static function avatar_post($user_id, $user, $url, $size)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::avatar_post_valid($user_id, $user, $url, $size, $out);

        if (self::$result['success']) {
            UserManager::avatar_post($out['url'], $out['user']);
            self::$result['user_id'] = $out['user']['id'];
        }
        return self::$result;
    }
    private static function avatar_post_valid($user_id, $user, $url, $size, $out)
    {
        self::$result = ['success' => false];

        $user_inf = User::find(intval($user_id));
        if(is_null($user_inf)) return self::$result['message'] = 'Пользователь не найден';
        $out['user'] = $user_inf;

        if($user_inf->id != $user->id) {
            if($user->role_id < 11 ) return self::$result['message'] = 'Отсуствую права для установки аватара';
        }

        if (is_null($url)) return self::$result['message'] = 'Проверьте загружаемый файл';
        //'/storage/avatars/'
        $out['url'] = '/uploads/avatars/'.$url;

        if (is_null($size)) return self::$result['message'] = 'Проверьте загружаемый файл';
        if ($size < 1) return self::$result['message'] = 'Проверьте загружаемый файл';
        $out['size'] = $size;

        self::$result = ['success' => true];
    }

    public static function avatar_del($user_id, $user)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::avatar_del_valid($user_id, $user, $out);

        if (self::$result['success']) {
            UserManager::avatar_del($out['user']);
            self::$result['user_id'] = $out['user']['id'];
        }
        return self::$result;
    }
    private static function avatar_del_valid($user_id, $user, $out)
    {
        self::$result = ['success' => false];

        $user_inf = User::find(intval($user_id));
        if(is_null($user_inf)) return self::$result['message'] = 'Пользователь не найден';
        $out['user'] = $user_inf;

        if(is_null($user_inf->avatar)) return self::$result['message'] = 'Пользовательский аватар не был установлен ранее';

        if($user_inf->id != $user->id) {
            if($user->role_id < 11 ) return self::$result['message'] = 'Отсуствую права для удаления аватара';
        }

        self::$result = ['success' => true];
    }
}
