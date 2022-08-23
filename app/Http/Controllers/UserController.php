<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\UserViewer;
use App\AppForum\Executors\UserExecutor;

class UserController extends Controller
{
    public function index($user_id)
    {
        $user = $this->user();

        $model = UserViewer::index($user_id, $user);

        return view('user.index', compact('model'));
    }

    public function role($user_id)
    {
        $user = $this->user();
        $result = UserExecutor::role($user_id, $user,  request()->all());

        if($result['success'])
        {
            return redirect('user/'.$result['user_id'])->with(['messageCancel' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
