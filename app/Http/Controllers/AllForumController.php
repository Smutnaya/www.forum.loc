<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\AllForumViewer;
class AllForumController extends Controller
{
    public function index()
    {
        //TODO: раздел?
        // TODO: model?

        $user = $this->user();
        $model = AllForumViewer::index($user);

        return view('allForum.index', compact('model'));
    }
}
