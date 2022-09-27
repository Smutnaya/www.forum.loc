<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Viewers\ComplaintViewer;
use App\AppForum\Executors\ComplaintExecutor;

class ComplaintController extends Controller
{
    public function request($post_id, $page = 1)
    {
        $user = $this->user();
        $result = ComplaintExecutor::request($post_id, $user, $page);
        if ($result['success']) {
            return redirect('t/' . $result['topicId'] . '/' . $result['page']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function index()
    {
        $user = $this->user();
        $model = ComplaintViewer::index($user);
        return view('complaint.index', compact('model'));
    }

    public function ok($complaint_id)
    {
        $user = $this->user();
        $result = ComplaintExecutor::ok($complaint_id, $user);
        if ($result['success']) {
            return redirect('/cw')->with(['messageOk' => $result['message']]);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function no($complaint_id)
    {
        $user = $this->user();
        $result = ComplaintExecutor::no($complaint_id, $user);
        if ($result['success']) {
            return redirect('/cw')->with(['messageOk' => $result['message']]);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
