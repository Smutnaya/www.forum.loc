<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Executors\OtherRoleExecutor;

class OtherRoleController extends Controller
{

    public function role_section($user_id)
    {
        $user = $this->user();
        $result = OtherRoleExecutor::role_section($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function role_forum($user_id)
    {
        $user = $this->user();
        $result = OtherRoleExecutor::role_forum($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function role_topic($user_id)
    {
        $user = $this->user();
        $result = OtherRoleExecutor::role_topic($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function moder_false($role_id)
    {
        $user = $this->user();
        $result = OtherRoleExecutor::moder_false($role_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function moder_true($role_id)
    {
        $user = $this->user();
        $result = OtherRoleExecutor::moder_true($role_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function del($role_id)
    {
        $user = $this->user();
        $result = OtherRoleExecutor::del($role_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }


}
