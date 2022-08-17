<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Executors\LikeExecutor;

class LikeController extends Controller
{
    public function like($postId, $page = 1)
    {
        $user = $this->user();
        $result = LikeExecutor::like($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function likem($postId, $page = 1)
    {
        $user = $this->user();
        $result = LikeExecutor::likem($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function dislike($postId, $page = 1)
    {
        $user = $this->user();
        $result = LikeExecutor::dislike($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function dislikem($postId, $page = 1)
    {
        $user = $this->user();
        $result = LikeExecutor::dislikem($postId, $user, $page);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId'].'/'.$result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
