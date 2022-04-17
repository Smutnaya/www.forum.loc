<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\SectionViewer;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index($sectionId)
    {
        $model = SectionViewer::index($sectionId);
        //dd($model);

        return view('section', compact('model'));
    }
}
