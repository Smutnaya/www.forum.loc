<?php

namespace App\Http\Controllers;

use App\AppForum\Viewers\SectionViewer;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index($sectionId)
    {
        $user = $this->user();
        $model = SectionViewer::index($sectionId, $user);

        return view('section.index', compact('model'));
    }
}
