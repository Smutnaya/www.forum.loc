<?php
namespace App\AppForum\Helpers;

use function PHPUnit\Framework\isNull;

class ForumHelper
{
    public static function jsonDecode($data)
    {
        return (json_decode($data, true));
    }
    public static function jsonEncode($data)
    {
        return (json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
