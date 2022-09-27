<?php

namespace App\AppForum\Helpers;

class ClanAllianceHelper
{
    public static $result = false;

    public static function userClan($user, $forum)
    {
        if (is_null($user)) return false;
        if (is_null($user->clan_id)) return false;
        if (is_null($forum)) return false;
        if (is_null($forum->clan_id)) return false;
        if ($user->clan_id != $forum->clan_id) return false;

        return true;
    }
    public static function userClanModer($user, $forum)
    {
        if (!self::userClan($user, $forum)) return false;
        return $user->clan_role < 20 ? false : true;
    }

    public static function userAlliance($user, $forum)
    {
        if (is_null($user)) return false;
        if (is_null($forum)) return false;
        if (is_null($user->alliance_id)) return false;
        if (is_null($forum->alliance_id)) return false;
        if ($user->alliance_id != $forum->alliance_id) return false;

        return true;
    }
    public static function userAllianceModer($user, $forum)
    {
        if (!self::userAlliance($user, $forum)) return false;

        return $user->clan_role >= 30 || $user->speaker ? true : false;
    }
}
