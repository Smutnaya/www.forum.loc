<?php

namespace App\AppForum\Viewers;

use App\Like;
use App\Post;
use App\Topic;
use App\Section;
use App\AppForum\Helpers\ForumHelper;
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
            'posts' => collect(),

            'pagination' => collect([
                'page' => null,
                'pages' => null,
                'topicId' => null,
            ]),
        ]);
    }

    public static function index($topicId, $user, $page)
    {
        $model = self::init();

        if (!is_null($user)) $model['user'] = $user;

        $topic = Topic::find(intval($topicId)); // eloquent
        if (is_null($topic)) return $model;
        self::setTopic($model, $topic);

        $model['forum'] = $topic->forum;
        $section = Section::all();
        if (is_null($section)) return $model;
        $model['section'] = $section;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic(intval($topicId));

        $topicPage = ForumHelper::topicPage($topicId);
        $pages = $topicPage['pages'];
        $take = $topicPage['take'];
        $page = ForumHelper::parsePage($page, $pages);
        $skip = ($page - 1) * $take;

        $posts = self::getPost(intval($topicId), $skip, $take);
        if ($posts->isEmpty()) return $model;
        self::setPost($model, $posts, $user);

        $model['pagination']['topicId'] = $topic->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        //dd($model['pagination']);

        return $model;

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
    }

    public static function getPost($topicId, $skip = null, $take = null)
    {
        if (!is_null($skip)) {
            return Post::where('topic_id', $topicId)->skip($skip)->take($take)->get();
        }

        return Post::where('topic_id', $topicId)->get();
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
            'datetime' => ForumHelper::timeFormat($topic->datetime),
            'datetime_d' => $topic->datetime,
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
                    'date' => ForumHelper::timeFormat($post->datetime),
                    'date_d' => $post->datetime,
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
                    'date' => ForumHelper::timeFormat($post->datetime),
                    'date_d' => $post->datetime,
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
