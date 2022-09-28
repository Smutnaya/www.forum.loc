<?php

namespace App\AppForum\Helpers;

class FilterHelper
{
    public static function sanitize($str)
    {
        return addslashes(htmlspecialchars($str));
        //return filter_var($str, FILTER_SANITIZE_STRING); // PHP 8 not supported
    }
}