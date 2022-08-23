<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\UserViewer;
use App\AppForum\Executors\BanExecutor;

class BanController extends Controller
{

    public function forum_ban($user_id)
    {
        $user = $this->user();
        $result = BanExecutor::forum_ban($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['message' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function section_ban($user_id)
    {
        $user = $this->user();
        $result = BanExecutor::section_ban($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['message' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function topic_ban($user_id)
    {
        $user = $this->user();
        $result = BanExecutor::topic_ban($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['message' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function forum_out($user_id)
    {
        $user = $this->user();
        $result = BanExecutor::forum_out($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['message' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function cancel($ban_id)
    {
        $user = $this->user();
        $result = BanExecutor::cancel($ban_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
