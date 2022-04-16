<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\TopicViewer;
use Illuminate\Http\Request;

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
        dd(request()->input('text'));

    }
}
