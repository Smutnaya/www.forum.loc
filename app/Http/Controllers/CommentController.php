<?php

namespace App\Http\Controllers;

use App\AppForum\Executors\CommentExecutor;
use Illuminate\Http\Request;
use App\AppForum\Viewers\PostViewer;

class CommentController extends Controller
{
    // public function edit($postId, $page = 1)
    // {
    //     if (!request()->isMethod('post')) return redirect('/');

    //     $user = $this->user();

    //     $result = PostExecutor::save($postId, $user, request()->all(), $page);
    //     if ($result['success']) {
    //         return redirect('t/' . $result['topicId'] . '/' . $result['page']);
    //     }

    //     return redirect()->back()->withErrors(['message' => $result['message']]);
    // }

    public function del($comment_id)
    {
        $user = $this->user();
        $result = CommentExecutor::del($comment_id, $user);
        if ($result['success']) {
            return redirect('t/' . $result['topicId']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
