<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\UserViewer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($user_id)
    {
        $user = $this->user();

        $model = UserViewer::index($user_id, $user);

        return view('user.index', compact('model'));
    }
}
