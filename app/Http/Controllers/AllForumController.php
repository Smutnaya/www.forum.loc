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

        $model = AllForumViewer::index();
       // dd($model);

        return view('allForum.index', compact('model'));
    }
}
