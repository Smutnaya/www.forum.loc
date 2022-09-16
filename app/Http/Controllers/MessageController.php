<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\MessageViewer;
use App\AppForum\Executors\MessageExecutor;

class MessageController extends Controller
{
    public function index()
    {
        $user = $this->user();
        $model = MessageViewer::index($user, $page = 1);
        return view('message.index', compact('model'));
    }

    public function new_message()
    {
        $user = $this->user();
        $model = MessageViewer::new_message($user);
        return view('message.inc.new', compact('model'));
    }

    public function save_message()
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();
        $result = MessageExecutor::save_message($user, request()->all());
        if($result['success'])
        {
            return redirect('/message')->with(['messageOk' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function history($message_id)
    {
        $user = $this->user();
        $model = MessageViewer::history($user, $message_id);
        return view('message.inc.message', compact('model'));
    }
    public function reply($message_id)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();
        $result = MessageExecutor::reply($user, $message_id, request()->all());
        if($result['success'])
        {
            return redirect('/message')->with(['messageOk' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function hide($message_id)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();
        $result = MessageExecutor::hide($user, $message_id, request()->all());
        if($result['success'])
        {
            return redirect('/message')->with(['messageOk' => $result['message']]);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function user_message($post_id)
    {
        $user = $this->user();
        $model = MessageViewer::user_message($user, $post_id);
        return view('message.inc.new', compact('model'));
    }
}
