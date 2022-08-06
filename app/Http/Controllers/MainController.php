<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\MainViewer;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $user = $this->user();

        $model = MainViewer::index($user);

        return view('main.index', compact('model'));
    }
}
