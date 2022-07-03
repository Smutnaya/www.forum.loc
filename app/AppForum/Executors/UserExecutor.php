<?php

namespace App\AppForum\Executors;

use App\AppForum\Helpers\IpHelper;

class Userxecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function post($url, $user)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false];
        else self::$result['success'] = true;
        $ip = IpHelper::getIp();

        if (self::$result['success']) {
            //UserManager::post($user, $ip, $url);

        }

        return self::$result;
    }
}
