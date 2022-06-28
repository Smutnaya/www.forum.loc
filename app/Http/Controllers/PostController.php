<?php

namespace App\Http\Controllers;

use App\AppForum\Executors\PostExecutor;
use Illuminate\Http\Request;
use App\AppForum\Viewers\PostViewer;

class PostController extends Controller
{
    public function index($postId)
    {
        $user = $this->user();
        $model = PostViewer::index($postId, $user);
        return view('topic.postEdit', compact('model'));
    }

    public function edit($postId)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();

        $result = PostExecutor::save($postId, $user, request()->all());
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function premod($postId)
    {
        $user = $this->user();
        $result = PostExecutor::premod($postId, $user);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
    public function unhide($postId)
    {
        $user = $this->user();
        $result = PostExecutor::unhide($postId, $user);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
