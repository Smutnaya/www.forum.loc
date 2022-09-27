<?php

namespace App\AppForum\Managers;

use App\Complaint;

class ComplaintManager
{
    public static function request($input)
    {
        return $complaint = Complaint::create([
            'ip' => $input['ip'],
            'datetime' => time(),
            'user_id' => $input['user']['id'],
            'post_id' => $input['post']['id']
        ]);
    }

    public static function close($complaint, $close, $DATA)
    {
        return $complaint->fill([
            'close' => $close,
            'DATA' => $DATA,
        ])->save();
    }
}
