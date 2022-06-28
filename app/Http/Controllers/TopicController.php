<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\TopicViewer;
use App\AppForum\Executors\TopicExecutor;

class TopicController extends Controller
{
    public function index($topicId)
    {
        $user = $this->user();
        $model = TopicViewer::index($topicId, $user);
        return view('topic.index', compact('model'));
    }

    public function post($topicId)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();
        $result = TopicExecutor::post($topicId, $user, request()->all());
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function edit($topicId)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();
        $result = TopicExecutor::edit($topicId, $user, request()->all());
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
