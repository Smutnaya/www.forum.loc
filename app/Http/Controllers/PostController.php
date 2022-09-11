<?php

namespace App\Http\Controllers;

use App\AppForum\Executors\PostExecutor;
use Illuminate\Http\Request;
use App\AppForum\Viewers\PostViewer;

class PostController extends Controller
{
    public function index($postId, $page = 1)
    {
        $user = $this->user();
        $model = PostViewer::index($postId, $user, $page);
        return view('topic.postModer', compact('model'));
    }

    public function index_edit($postId, $page = 1)
    {
        $user = $this->user();
        $model = PostViewer::index($postId, $user, $page);
        return view('topic.postEdit', compact('model'));
    }

    public function edit($postId, $page = 1)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();

        $result = PostExecutor::save($postId, $user, request()->all(), $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function moder($postId, $page = 1)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user();

        $result = PostExecutor::save_moder($postId, $user, request()->all(), $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function premod($postId, $page = 1)
    {
        $user = $this->user();
        $result = PostExecutor::premod($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
    public function unhide($postId, $page = 1)
    {
        $user = $this->user();
        $result = PostExecutor::unhide($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function del($postId, $page = 1)
    {
        $user = $this->user();
        $result = PostExecutor::del($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
