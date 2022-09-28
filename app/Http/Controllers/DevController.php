<?php

namespace App\Http\Controllers;

use App\UserGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AppForum\Viewers\ForumViewer;
use App\AppForum\Executors\ForumExecutor;

class DevController extends Controller
{
    public function index()
    {
       
        $usergame = UserGame::where('title', 'Димитар')->first();

        //dd($usergame->password.' | '.Hash::make('qqqqqq'));

        dd(Hash::check('qqqqqq', $usergame->password));
    }
}