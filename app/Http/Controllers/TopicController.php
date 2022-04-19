<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\TopicViewer;
use App\AppForum\Executors\TopicExecutor;

class TopicController extends Controller
{
    public function index($topicId)
    {
        // TODO: а сушествует ли такая тема?

        //dd($topicId);
        $model = TopicViewer::index($topicId);
        //dd($model);

        return view('topic', compact('model'));

    }

    public function post($topicId)
    {
        if(!request()->isMethod('post')) return redirect('/');

        $user = $this->user(); //null ili net $user->email, is_null($user)

        $result = TopicExecutor::post($topicId, $user, request()->all());
        //$result = TopicExecutor::post($topicId, request()->all());
        if($result['success'])
        {
            //$model = TopicViewer::index($topicId);

            return redirect('t/'.$result['topicId']);
            //return view('topic', compact('model'));
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
