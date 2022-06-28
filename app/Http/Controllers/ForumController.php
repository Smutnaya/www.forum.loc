<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\ForumViewer;
use App\AppForum\Executors\ForumExecutor;
class ForumController extends Controller
{
    public function index($forumId)
    {
        //TODO: раздел?
        // TODO: model?

        $model = ForumViewer::index($forumId);
        return view('forum.index', compact('model', 'forumId'));
    }

    public function topic($forumId)
    {
        $user = $this->user();
        $result = ForumExecutor::forum($forumId, $user);

        if($result['success'])
        {
            $model = ForumViewer::topic($forumId);
            return view('forum.topic', compact('model', 'forumId'));
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);

    }

    public function save($forumId)
    {
        if(!request()->isMethod('post')) return redirect('/');
        $user = $this->user(); //null ili net $user->email, is_null($user)
        $result = ForumExecutor::post($forumId, $user, request()->all());

        if($result['success'])
        {
            return redirect('t/'.$result['topicId']);
        }

        //dd($result);

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
