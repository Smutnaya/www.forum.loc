<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\ForumViewer;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index($forumId)
    {
        $model = ForumViewer::index($forumId);
        //dd($model);

        return view('forum.index', compact('model', 'forumId'));
    }

    public function topic($forumId)
    {
        return view('forum.topic', compact('forumId'));
    }
}
