<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\MainViewer;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $model = MainViewer::index();

        return view('main.index', compact('model'));
    }
}
