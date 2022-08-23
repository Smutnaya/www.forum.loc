<?php

namespace App\AppForum\Executors;

use App\User;
use App\Forum;
use App\Topic;
use App\Section;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\OtherRoleManager;
use App\Other_role;

class OtherRoleExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function role_section($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::role_section_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $other_role = OtherRoleManager::role_section($out);
            self::$result['user_id'] = $out['user_role']['id'];
        }
        return self::$result;
    }
    private static function role_section_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_role = User::find(intval($user_id));
        if (is_null($user_role)) return self::$result['message'] = 'Недостаточно прав';
        $out['user_role'] = $user_role;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_role->id == $user_moder->id) return self::$result['message'] = 'Не возможно предоставить доступ самому себе';
        if ($user_role->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав';

        if (empty($input['check1'])) return self::$result['message'] = 'Не выбран форум';

        $section = Section::find(intval($input['check1']['0']));
        if (is_null($section)) return self::$result['message'] = 'Форум не найден';
        $out['section'] = $section;

        $roles = Other_role::where([['user_id', $user_role->id], ['section_id', $section->id]])->get();

        if($roles->count() > 0) return self::$result['message'] = 'Доступ уже предоставлен ранее';

        if ($user_moder->role_id < 11) return self::$result['message'] = 'Недостаточно прав';

        if (empty($input['moder'])){
            $out['moderation'] = false;
            self::$result = ['success' => true, 'message' => 'Доступ для ' . $out['user_role']['name'] . ' добавлен - форум "' . $out['section']['title'] . '"' ];
        } else {
            $out['moderation'] = true;
            self::$result = ['success' => true, 'message' => 'Доступ для ' . $out['user_role']['name'] . ' добавлен - форум "' . $out['section']['title'] . '" (модерация)' ];
        }
    }

    public static function role_forum($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::role_forum_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $other_role = OtherRoleManager::role_forum($out);
            self::$result['user_id'] = $out['user_role']['id'];
        }
        return self::$result;
    }
    private static function role_forum_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_role = User::find(intval($user_id));
        if (is_null($user_role)) return self::$result['message'] = 'Недостаточно прав';
        $out['user_role'] = $user_role;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_role->id == $user_moder->id) return self::$result['message'] = 'Не возможно предоставить доступ самому себе';
        if ($user_role->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав';

        if (empty($input['check2'])) return self::$result['message'] = 'Не выбрана ветка форума';

        $forum = Forum::find(intval($input['check2']['0']));
        if (is_null($forum)) return self::$result['message'] = 'Ветка форума не найдена';
        $out['forum'] = $forum;

        $roles = Other_role::where([['user_id', $user_role->id], ['forum_id', $forum->id]])->get();

        if($roles->count() > 0) return self::$result['message'] = 'Доступ уже предоставлен ранее';

        if ($user_moder->role_id < 11) return self::$result['message'] = 'Недостаточно прав';

        if (empty($input['moder'])){
            $out['moderation'] = false;
            self::$result = ['success' => true, 'message' => 'Доступ для ' . $out['user_role']['name'] . ' добавлен - ветка форума "' . $out['forum']['title'] . '"' ];
        } else {
            $out['moderation'] = true;
            self::$result = ['success' => true, 'message' => 'Доступ для ' . $out['user_role']['name'] . ' добавлен - ветка форума "' . $out['forum']['title'] . '" (модерация)' ];
        }
    }

    public static function role_topic($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::role_topic_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $other_role = OtherRoleManager::role_topic($out);
            self::$result['user_id'] = $out['user_role']['id'];
        }
        return self::$result;
    }
    private static function role_topic_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_role = User::find(intval($user_id));
        if (is_null($user_role)) return self::$result['message'] = 'Недостаточно прав';
        $out['user_role'] = $user_role;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_role->id == $user_moder->id) return self::$result['message'] = 'Не возможно предоставить доступ самому себе';
        if ($user_role->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав';

        if (is_null($input['id'])) return self::$result['message'] = 'Не выбрана тема';

        $topic = Topic::find(intval($input['id']));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';
        $out['topic'] = $topic;

        $roles = Other_role::where([['user_id', $user_role->id], ['topic_id', $topic->id]])->get();

        if($roles->count() > 0) return self::$result['message'] = 'Доступ уже предоставлен ранее';

        if ($user_moder->role_id < 11) return self::$result['message'] = 'Недостаточно прав';

        if (empty($input['moder'])){
            $out['moderation'] = false;
            self::$result = ['success' => true, 'message' => 'Доступ для ' . $out['user_role']['name'] . ' добавлен - тема "' . $out['topic']['title'] . '"' ];
        } else {
            $out['moderation'] = true;
            self::$result = ['success' => true, 'message' => 'Доступ для ' . $out['user_role']['name'] . ' добавлен - тема "' . $out['topic']['title'] . '" (модерация)' ];
        }
    }

    public static function moder_false($role_id, $user, $input)
    {
        $out = collect();

        self::moder_valid(intval($role_id), $input, $out, $user);

        if (self::$result['success']) {
            OtherRoleManager::moder_false($out['role']);
            self::$result['user_id'] = $out['role']['user_id'];
            self::$result['message'] = 'Права на модерацию удалены';
        }
        return self::$result;
    }
    public static function moder_true($role_id, $user, $input)
    {
        $out = collect();

        self::moder_valid(intval($role_id), $input, $out, $user);

        if (self::$result['success']) {
            OtherRoleManager::moder_true($out['role']);
            self::$result['user_id'] = $out['role']['user_id'];
            self::$result['message'] = 'Права на модерацию успешно добавлены';
        }
        return self::$result;
    }
    public static function del($role_id, $user, $input)
    {
        $out = collect();

        self::moder_valid(intval($role_id), $input, $out, $user);

        if (self::$result['success']) {
            OtherRoleManager::del($out['role']);
            self::$result['user_id'] = $out['role']['user_id'];
            self::$result['message'] = 'Доступ успешно удален';
        }
        return self::$result;
    }
    private static function moder_valid($role_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $role = Other_role::find(intval($role_id));
        if (is_null($role)) return self::$result['message'] = 'Данные о предоставленном доступе на найдены';
        $out['role'] = $role;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($role->user_id == $user_moder->id) return self::$result['message'] = 'Не возможно редактировать доступ самому себе';
        if ($role->user->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав';
        if ($user_moder->role_id < 11) return self::$result['message'] = 'Недостаточно прав';

        self::$result['success'] = true;
    }


}
