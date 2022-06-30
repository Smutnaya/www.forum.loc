<?php

namespace App\AppForum\Managers;

use App\Forum;

class ForumManager
{
    public static function dataedit($forum, $DATA)
    {
        $forum->fill([
            'DATA' => $DATA
        ])->save();
    }
}
