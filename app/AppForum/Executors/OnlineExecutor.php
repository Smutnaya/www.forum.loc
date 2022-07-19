<?php

namespace App\AppForum\Executors;

use App\AppForum\Helpers\IpHelper;
use App\AppForum\Managers\OnlineManager;

class OnlineExecutor extends BaseExecutor
{
    public static $result = ['success' => false];

    public static function post($url, $user)
    {
        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false];
        else self::$result['success'] = true;

        if(is_null($url)) self::$result = ['success' => false];
        else self::$result['success'] = true;
        $ip = IpHelper::getIp();

        if (self::$result['success']) {
            if(is_null($user->online)) {
                OnlineManager::post($user, $ip, $url);
            } elseif($user->online->datetime < strtotime('-5 minute')) {
                OnlineManager::updata($user, $ip, $url);
            }
        }
    }
}
