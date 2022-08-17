<?php

namespace App\AppForum\Executors;

use App\Ban;
use App\User;
use App\Forum;
use App\Topic;
use App\Section;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Managers\BanManager;

class BanExecutor extends BaseExecutor
{
    public static $result = ['success' => false, 'message' => null];

    public static function forum_ban($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::forum_ban_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $ban = BanManager::ban_forum($out);
            self::$result['user_id'] = $out['user_ban']['id'];
        }
        return self::$result;
    }
    private static function forum_ban_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_ban = User::find(intval($user_id));
        if (is_null($user_ban)) return self::$result['message'] = 'Пользователь не найден';
        $out['user_ban'] = $user_ban;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_ban->id == $user_moder->id) return self::$result['message'] = 'Не стоит себя банить :)';
        if ($user_ban->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав для блокировки';

        if (empty($input['check'])) return self::$result['message'] = 'Не выбрана ветка форума';

        $forum = Forum::find(intval($input['check']['0']));
        if (is_null($forum)) return self::$result['message'] = 'Ветка форума не найдена';
        $out['forum'] = $forum;

        if (!ModerHelper::moderPost($user_moder->role_id, $forum->id, $forum->section_id, $user_moder, $forum->topic_id)) return self::$result['message'] = 'Недостаточно прав для блокировки';

        if (is_null($input['text'])) return self::$result['message'] = 'Введите причину блокировки пользователя';

        if (mb_strlen($input['text']) > 500 && !is_null($input['text'])) {
            $out['text'] = mb_strimwidth($input['text'], 0, 500, "...");
        } else {
            $out['text'] = $input['text'];
        }

        $time_ban = ForumHelper::dHiToInt($input['d'], $input['h'], $input['m']);

        if ($time_ban <= 0) return self::$result['message'] = 'Некорректное время блокировки';
        if ($time_ban > 86400 && ($user_moder->role_id == 2 || $user_moder->role_id == 5)) $time_ban = 86400;
        $time_ban_str = ForumHelper::timeStr($time_ban);

        //$out['datetime_ban'] = $time_ban;
        $out['datetime_end'] = $time_ban + time();
        $out['datetime_str'] = $time_ban_str . ' (до ' . date('d.m.Y H:i', $out['datetime_end']) . ')'; // TODO добавить столбец


        self::$result = ['success' => true, 'message' => 'Пользователь ' . $out['user_ban']['name'] . ' заблокирован в "' . $out['forum']['title'] . '" сроком на ' .  $out['datetime_str']];
    }

    public static function section_ban($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::section_ban_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $ban = BanManager::ban_section($out);
            self::$result['user_id'] = $out['user_ban']['id'];
        }
        return self::$result;
    }
    private static function section_ban_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_ban = User::find(intval($user_id));
        if (is_null($user_ban)) return self::$result['message'] = 'Пользователь не найден';
        $out['user_ban'] = $user_ban;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_ban->id == $user_moder->id) return self::$result['message'] = 'Не стоит себя банить :)';
        if ($user_ban->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав для блокировки';

        if (empty($input['check1'])) return self::$result['message'] = 'Не выбран форум';

        $section = Section::find(intval($input['check1']['0']));
        if (is_null($section)) return self::$result['message'] = 'Форум не найден';
        $out['section'] = $section;

        if ($user_moder->role_id < 4 || $user_moder->role_id > 4 && $user_moder->role_id < 9) return self::$result['message'] = 'Недостаточно прав для блокировки';
        if (!ModerHelper::blockSection($user_moder->role_id, $section->id)) return self::$result['message'] = 'Недостаточно прав для блокировки';
        if (is_null($input['text'])) return self::$result['message'] = 'Введите причину блокировки пользователя';

        if (mb_strlen($input['text']) > 500 && !is_null($input['text'])) {
            $out['text'] = mb_strimwidth($input['text'], 0, 500, "...");
        } else {
            $out['text'] = $input['text'];
        }

        $time_ban = ForumHelper::dHiToInt($input['d'], $input['h'], $input['m']);

        if ($time_ban <= 0) return self::$result['message'] = 'Некорректное время блокировки';
        if ($time_ban > 86400 && ($user_moder->role_id == 2 || $user_moder->role_id == 5)) $time_ban = 86400;
        $time_ban_str = ForumHelper::timeStr($time_ban);

        //$out['datetime_ban'] = $time_ban;
        $out['datetime_end'] = $time_ban + time();
        $out['datetime_str'] = $time_ban_str . ' (до ' . date('d.m.Y H:i', $out['datetime_end']) . ')'; // TODO добавить столбец


        self::$result = ['success' => true, 'message' => 'Пользователь ' . $out['user_ban']['name'] . ' заблокирован на форуме "' . $out['section']['title'] . '" сроком на ' .  $out['datetime_str']];
    }

    public static function topic_ban($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::topic_ban_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $ban = BanManager::ban_topic($out);
            self::$result['user_id'] = $out['user_ban']['id'];
        }
        return self::$result;
    }
    private static function topic_ban_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_ban = User::find(intval($user_id));
        if (is_null($user_ban)) return self::$result['message'] = 'Пользователь не найден';
        $out['user_ban'] = $user_ban;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_ban->id == $user_moder->id) return self::$result['message'] = 'Не стоит себя банить :)';
        if ($user_ban->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав для блокировки';

        if (empty($input['id'])) return self::$result['message'] = 'Не выбрана тема';

        $topic = Topic::find(intval($input['id']));
        if (is_null($topic)) return self::$result['message'] = 'Тема не найдена';
        $out['topic'] = $topic;

        if (!ModerHelper::moderPost($user_moder->role_id, $topic->forum_id, $topic->forum->section_id, $user_moder, $topic->id)) return self::$result['message'] = 'Недостаточно прав для блокировки';

        if (is_null($input['text'])) return self::$result['message'] = 'Введите причину блокировки пользователя';

        if (mb_strlen($input['text']) > 500 && !is_null($input['text'])) {
            $out['text'] = mb_strimwidth($input['text'], 0, 500, "...");
        } else {
            $out['text'] = $input['text'];
        }

        $time_ban = ForumHelper::dHiToInt($input['d'], $input['h'], $input['m']);

        if ($time_ban <= 0) return self::$result['message'] = 'Некорректное время блокировки';
        if ($time_ban > 86400 && ($user_moder->role_id == 2 || $user_moder->role_id == 5)) $time_ban = 86400;
        $time_ban_str = ForumHelper::timeStr($time_ban);

        //$out['datetime_ban'] = $time_ban;
        $out['datetime_end'] = $time_ban + time();
        $out['datetime_str'] = $time_ban_str . ' (до ' . date('d.m.Y H:i', $out['datetime_end']) . ')'; // TODO добавить столбец


        self::$result = ['success' => true, 'message' => 'Пользователь ' . $out['user_ban']['name'] . ' заблокирован в теме "' . $out['topic']['title'] . '" сроком на ' .  $out['datetime_str']];
    }

    public static function forum_out($user_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];

        if (self::$result['success']) self::forum_out_valid(intval($user_id), $input, $out, $user);

        if (self::$result['success']) {
            $ban = BanManager::forum_out($out);
            self::$result['user_id'] = $out['user_ban']['id'];
        }
        return self::$result;
    }
    private static function forum_out_valid($user_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $user_ban = User::find(intval($user_id));
        if (is_null($user_ban)) return self::$result['message'] = 'Пользователь не найден';
        $out['user_ban'] = $user_ban;

        if (!is_null($user_moder)) $out['user_moder'] = $user_moder;

        if ($user_ban->id == $user_moder->id) return self::$result['message'] = 'Не стоит себя банить :)';
        if ($user_ban->role_id > $user_moder->role_id) return self::$result['message'] = 'Недостаточно прав для блокировки';
        if ($user_moder->role_id < 11) return self::$result['message'] = 'Недостаточно прав для блокировки';

        if (is_null($input['text'])) return self::$result['message'] = 'Введите причину блокировки пользователя';

        if (mb_strlen($input['text']) > 500 && !is_null($input['text'])) {
            $out['text'] = mb_strimwidth($input['text'], 0, 500, "...");
        } else {
            $out['text'] = $input['text'];
        }

        $time_ban = ForumHelper::dHiToInt($input['d'], $input['h'], $input['m']);

        if ($time_ban <= 0) return self::$result['message'] = 'Некорректное время блокировки';
        if ($time_ban > 86400 && ($user_moder->role_id == 2 || $user_moder->role_id == 5)) $time_ban = 86400;
        $time_ban_str = ForumHelper::timeStr($time_ban);

        //$out['datetime_ban'] = $time_ban;
        $out['datetime_end'] = $time_ban + time();
        $out['datetime_str'] = $time_ban_str . ' (до ' . date('d.m.Y H:i', $out['datetime_end']) . ')';


        self::$result = ['success' => true, 'message' => 'Пользователь ' . $out['user_ban']['name'] . ' заблокирован на ФОРУМЕ сроком на ' .  $out['datetime_str']];
    }

    public static function cancel($ban_id, $user, $input)
    {
        $out = collect();

        if (!is_null(BaseExecutor::user_valid($user))) self::$result = ['success' => false, 'message' => BaseExecutor::user_valid($user)];
        else self::$result = ['success' => true];
        $out['user_moder'] = $user;

        if (self::$result['success']) self::cancel_valid(intval($ban_id), $input, $out, $user);

        if (self::$result['success']) {
            BanManager::cancel($out['ban'], $out);
            self::$result['user_id'] = $out['ban']['user_id'];
        }
        return self::$result;
    }
    private static function cancel_valid($ban_id, $input, $out, $user_moder)
    {
        self::$result = ['success' => false];

        $ban = Ban::find(intval($ban_id));
        if (is_null($ban)) return self::$result['message'] = 'Данные о блокировке пользователя не найден';
        $out['ban'] = $ban;

        if ($ban->user_id == $user_moder->id) return self::$result['message'] = 'Невозможно отменить бан самим себе :(';
        if (!ModerHelper::banCancel($user_moder, $ban->id)) return self::$result['message'] = 'Недостаточно прав для отмены блокировки';

        if (is_null($input['text'])) return self::$result['message'] = 'Введите причину отмены блокировки';

        if (mb_strlen($input['text']) > 500 && !is_null($input['text'])) {
            $out['text'] = mb_strimwidth($input['text'], 0, 500, "...");
        } else {
            $out['text'] = $input['text'];
        }

        self::$result = ['success' => true, 'message' => 'Блокировка успешно отмена'];
    }
}
