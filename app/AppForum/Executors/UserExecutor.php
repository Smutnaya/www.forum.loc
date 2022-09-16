<?php

namespace App\AppForum\Executors;

use App\Role;
use App\User;
use App\Forum;
use App\AppForum\Helpers\IpHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\UserManager;

class UserExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function role($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::role_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            UserManager::role($out['user'], $out);
            self::$result['user_id'] = $out['user']['id'];
        }
        return self::$result;
    }
    private static function role_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user = User::find(intval($user_id));
        if (is_null($user)) return self::$result['message'] = 'Данные о пользователе не найдены';

        if (empty($input['check'])) return self::$result['message'] = 'Не выбран статус';

        $role = Role::find(intval($input['check']['0']));
        if (is_null($role)) return self::$result['message'] = 'Данные о статусе не найдены';
        $out['role'] = $role;

        if(!ModerHelper::roles($user_moder, $user)) return self::$result['message'] = 'Не достаточно правд для изменения статуса пользователю';
        if(!self::roleInstall_valid($user, $user_moder)) return self::$result['message'] = 'Не достаточно правд для изменения статуса на указаный';

        $out['user'] = $user;

        self::$result = ['success' => true, 'message' => 'Статус успешно изменен на '.  $role->role];
    }
    private static function roleInstall_valid($user, $user_moder)
    {
        $result = false;

        if ($user_moder->role_id == 4 && $user->role_id < 4) return true;
        if ($user_moder->role_id == 9 && $user->role_id != 4  && $user->role_id < 9) return true;
        if ($user_moder->role_id == 10 && $user->role_id != 4  && $user->role_id < 10) return true;
        if ($user_moder->role_id == 11 && $user->role_id < 11) return true;
        if ($user_moder->role_id == 12) return true;

        return $result;
    }

    public static function ban_message($user_id, $user)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::ban_message_valid(intval($user_id), $out, $user);

        if (self::$result['success']) {
            UserManager::ban_message($out['user'], $out['ban_message']);
            self::$result['user_id'] = $out['user']['id'];
        }
        return self::$result;
    }
    private static function ban_message_valid($user_id, $out, $user)
    {
        self::$result = ['success' => false];

        $user_ban = User::find(intval($user_id));
        if (is_null($user_ban)) return self::$result['message'] = 'Данные о пользователе не найдены';
        $out['user'] = $user_ban;
        if($user_ban->ban_message)
        {
            $out['ban_message'] = false;
            self::$result['message'] = 'Пользователю '.  $user_ban->name .' снято ограничение в праве отправки ПМ';
        } else {
            $out['ban_message'] = true;
            self::$result['message'] = 'Пользователь '.  $user_ban->name .' ограничен в праве отправки ПМ';
        }

        if($user->role_id < 11) return self::$result['message'] = 'Не достаточно правд для блокировки';

        self::$result['success'] = true;
    }
}
