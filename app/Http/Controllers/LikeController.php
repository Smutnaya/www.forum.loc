<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Executors\LikeExecutor;

class LikeController extends Controller
{
    public function like($postId)
    {
        $user = $this->user();
        $result = LikeExecutor::like($postId, $user);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function likem($postId)
    {
        $user = $this->user();
        $result = LikeExecutor::likem($postId, $user);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function dislike($postId)
    {
        $user = $this->user();
        $result = LikeExecutor::dislike($postId, $user);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function dislikem($postId)
    {
        $user = $this->user();
        $result = LikeExecutor::dislikem($postId, $user);
        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
