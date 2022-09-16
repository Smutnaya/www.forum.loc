<?php

namespace App\AppForum\Managers;

use App\Message;

class MessageManager
{
    public static function new_message($input)
    {
        $message = Message::create([
            'user_id' => $input['user']['id'],
            'user_id_to' => $input['user_to']['id'],
            'title' => $input['title'],
            'text' => $input['text'],
            'datetime' => time(),
        ]);
    }

    public static function view($message)
    {
        $message->fill([
            'view' => true
        ])->save();
    }

    public static function hide($message)
    {
        $message->fill([
            'hide' => true
        ])->save();
    }
}
