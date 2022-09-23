<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Viewers\TopicViewer;
use App\AppForum\Executors\TopicExecutor;

class TopicController extends Controller
{
    public function index($topicId, $page = 1)
    {
        $user = $this->user();
        $model = TopicViewer::index(ForumHelper::getId($topicId), $user, $page);
        if (!is_null($model['topic'])) {
            TopicExecutor::view(ForumHelper::getId($topicId), $user);

            if ($model['section_id'] == 6){
                return view('blog.index', compact('model'));
            } else {
                return view('topic.index', compact('model'));
            }
        }
    }

    public function post($topicId)
    {
        if (!request()->isMethod('post')) return redirect('/');

        $user = $this->user();

        //TODO проверить закрыта ли тема для обычных юзеров

        $result = TopicExecutor::post(ForumHelper::getId($topicId), $user, request()->all());
        if ($result['success']) {
            return redirect('t/' . $result['topicId'] . '-' . $result['title_slug'] . '/end');
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function edit($topicId)
    {
        if (!request()->isMethod('post')) return redirect('/');

        $user = $this->user();
        $result = TopicExecutor::edit(ForumHelper::getId($topicId), $user, request()->all());
        if ($result['success']) {
            return redirect('t/' . $result['topicId'] . '-' . $result['title_slug']);
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function comment($topicId)
    {
        if (!request()->isMethod('post')) return redirect('/');

        $user = $this->user();

        $result = TopicExecutor::comment(ForumHelper::getId($topicId), $user, request()->all());
        if ($result['success']) {
            return redirect('t/' . $result['topicId'] . '-' . $result['title_slug'] . '/end');
        }

        return redirect()->back()->withErrors(['message' => $result['message']]);
    }

    public function move($topicId)
    {
        if (!request()->isMethod('post')) return redirect('/');

        $user = $this->user();

        $result = TopicExecutor::move(ForumHelper::getId($topicId), $user, request()->all());
        if ($result['success']) {
            return redirect('t/' . $result['topicId'] . '-' . $result['title_slug']);
        }
        return redirect()->back()->withErrors(['message' => $result['message']]);
    }
}
