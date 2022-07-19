<?php

namespace App\AppForum\Viewers;

use App\Like;
use App\Post;
use App\Topic;
use App\Section;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class TopicViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'user' => null,
            'forum' => null,
            'section' => null,
            'topic' => null,
            'posts' => collect()
        ]);
    }

    public static function index($topicId, $user)
    {
        $model = self::init();

        $topic = Topic::find(intval($topicId)); // eloquent
        if (is_null($topic)) return $model;
        self::setTopic($model, $topic);

        $posts = Post::where('topic_id', intval($topicId))->get();
        if ($posts->isEmpty()) return $model;
        self::setPost($model, $posts, $user);

        $model['forum'] = $topic->forum;
        $section = Section::all();
        if (is_null($section)) return $model;
        $model['section'] = $section;

        /*
        $filelist = array();
        if ($handle = opendir("C:/OpenServer/domains/www.forum.loc/public/images/smiley")) {
            while ($entry = readdir($handle)) {
                    $filelist[] = $entry;
            }
            closedir($handle);
        }
        dd($filelist);
*/
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic(intval($topicId));
        if (is_null($user)) return $model;


        $model['user'] = $user;

        return $model;
    }

    private static function setTopic($model, $topic)
    {
        $model['topic'] = [
            'title' => $topic->title,
            'hide' => $topic->hide,
            'block' => $topic->block,
            'pin' => $topic->pin,
            'moderation' => $topic->moderation,
            'id' => $topic->id,
            'datetime' => $topic->datetime,
            'user_id' => $topic->user_id
        ];
    }

    private static function setPost($model, $posts, $user)
    {
        foreach ($posts as $post) {
            if (!is_null($user)) {
                $model['posts']->push([
                    'text' => $post->text,
                    'ip' => $post->ip,
                    'date' => $post->datetime,
                    'hide' => $post->hide,
                    'moderation' => $post->moderation,
                    'DATA' => json_decode($post->DATA, false), //$post->DATA,
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'user_post' => $post->user,
                    'user_DATA' => json_decode($post->user->DATA, false),
                    'like' => Like::select('action')->where([['post_id', $post->id], ['user_id', $user->id]])->first()
                ]);
            } else {
                $model['posts']->push([
                    'text' => $post->text,
                    'ip' => $post->ip,
                    'date' => $post->datetime,
                    'hide' => $post->hide,
                    'moderation' => $post->moderation,
                    'DATA' => json_decode($post->DATA, false), //$post->DATA,
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'user_post' => $post->user,
                    'user_DATA' => json_decode($post->user->DATA, false),
                    'like' => null
                ]);
            }
        }
    }
}
