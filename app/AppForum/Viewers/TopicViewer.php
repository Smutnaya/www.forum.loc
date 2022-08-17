<?php

namespace App\AppForum\Viewers;

use App\Like;
use App\Post;
use App\Role;
use App\User;
use App\Topic;
use App\Section;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Viewers\SectionViewer;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class TopicViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'user' => null,
            'userBan' => false,
            'forum' => null,
            'section' => null,
            'topic' => null,
            'topicEdit' => false,
            'topicMove' => false,
            'posts' => collect(),
            'postsModer' => collect(),
            'sectionsAside' => collect(),
            'visit_forum' => false,
            'newPost' => null,

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

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

        if (!is_null($user)) $model['user'] = $user;
        $user_role = ModerHelper::user_role($user);

        $topic = Topic::find(intval($topicId)); // eloquent
        if (is_null($topic)) return $model;

        if (ModerHelper::visForum($user_role, $topic->forum_id, $topic->forum->section_id, $user) && self::visitTopic($topic, $user_role, $user))  $model['visit_forum'] = true;

        self::setTopic($model, $topic, $user_role);

        $model['forum'] = $topic->forum;

        if (!is_null($user)) {
            $section = ModerHelper::getSection($user);
        } else $section = null;

        if (!is_null($section)) $model['section'] = $section;

        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic(intval($topicId));

        $topicPage = ForumHelper::topicPage($topicId, $user_role);
        $pages = $topicPage['pages'];
        $take = $topicPage['take'];
        $page = ForumHelper::parsePage($page, $pages);
        $skip = ($page - 1) * $take;

        $posts = self::getPost(intval($topicId), $skip, $take, $user_role, $topic->forum_id, $topic->forum->section_id, $user);
        if ($posts->isEmpty()) return $model;
        self::setPost($model, $posts, $user, $user_role, $topic->forum_id, $topic->forum->section_id);

        $model['newPost'] = ModerHelper::moderPost($user_role, $topic->forum_id, $topic->forum->section_id, $user, $topic->id);
        $model['userBan'] = ModerHelper::banTopic($user, $topic);

        $model['pagination']['topicId'] = $topic->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        if (!is_null($user)) {
            $model['topicEdit'] = ModerHelper::moderTopicEdit($model['user']['role_id'], $model['user']['id'], $model['topic']['datetime_d'], $model['topic']['DATA'], $model['topic']['user_id'], $model['topic']['forum_id'], $model['topic']['section_id'], $model['topic']['id']);
            $model['topicMove'] = ModerHelper::moderTopicMove($model['user']['role_id'], $model['topic']['forum_id'], $model['topic']['section_id'], $user, $model['topic']['id']);
        }
        return $model;

        /*
        $filelist = array();
        if ($handle = opendir("C:/OpenServer/domains/www.forum.loc/public/images/smiley")) {
            while ($entry = readdir($handle)) {
                    $filelist[] = $entry;
            }
            closedir($handle);
        }
*/
    }

    public static function getPost($topicId, $skip = null, $take = null, $user_role, $forum_id, $section_id, $user)
    {
        $posts = collect();

        if ($user_role == 0 && !is_null($skip)) {
            $posts = Post::where([['topic_id', $topicId], ['moderation', false], ['hide', false]])->skip($skip)->take($take)->get();
        } elseif ($user_role == 0 && is_null($skip)) {
            $posts = Post::where([['topic_id', $topicId], ['moderation', false], ['hide', false]])->get();
        }

        if ($section_id == 3) {
            if ($user_role > 0 && $user_role < 8 && !is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role > 0 && $user_role < 8 && is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->get();
            }

            if ($user_role > 7 && !is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->skip($skip)->take($take)->get();
            } elseif ($user_role > 7 && is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->get();
            }
        } else {
            if ($user_role == 1 && !is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role == 1 && is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->get();
            }
            if ($user_role > 1 && !is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->skip($skip)->take($take)->get();
            } elseif ($user_role > 1 && is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->get();
            }
        }

        if ($forum_id == 1 || $forum_id == 3) {
            if ($user_role >= 1 && $user_role < 11 && !is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role >= 1 && $user_role < 11 && is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->get();
            }
            if ($user_role > 10 && !is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->skip($skip)->take($take)->get();
            } elseif ($user_role > 10 && is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->get();
            }
        }
        if ($forum_id == 2) {
            if ($user_role >= 1 && $user_role < 9 && !is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role >= 1 && $user_role < 9 && is_null($skip)) {
                $posts = Post::where([['topic_id', $topicId], ['hide', false]])->get();
            }

            if ($user_role > 8 && !is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->skip($skip)->take($take)->get();
            } elseif ($user_role > 8 && is_null($skip)) {
                $posts = Post::where('topic_id', $topicId)->get();
            }
        }


        return $posts;
    }

    private static function setTopic($model, $topic, $user_role)
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
            'DATA' => json_decode($topic->DATA, false),
            'user_id' => $topic->user_id,
            'forum_id' => $topic->forum_id,
            'section_id' => $topic->forum->section_id,
        ];
    }

    private static function setPost($model, $posts, $user, $user_role, $forum_id, $section_id)
    {
        foreach ($posts as $post) {
            $user_post = User::find($post->user_id);
            if (!is_null($user)) {
                if ($post->moderation) {
                    if ($section_id == 3) {
                        if ($user_role > 0 && $user_role < 8 && $post->user_id == $user->id) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        } elseif ($user_role > 7) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        }
                    } else {
                        if ($forum_id == 1 || $forum_id == 3) {
                            if ($user_role > 0 && $user_role < 11 && $post->user_id == $user->id) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            } elseif ($user_role > 10) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            }
                        } elseif ($forum_id == 2) {
                            if ($user_role >= 1 && $user_role < 9 && $post->user_id == $user->id) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            } elseif ($user_role > 8) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            }
                        } elseif ($forum_id == 52 || $forum_id == 53) {
                            if ($user_role >= 1 && $user_role < 12 && $post->user_id == $user->id) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            } elseif ($user_role > 11) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            }
                        } elseif ($user_role > 1) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        }
                    }
                } else {
                    self::visPost($post, $user_role, $user, $user_post, $model);
                }
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
                    'user_role' => Role::find($user_post->role_id)->description,
                    'user_role_style' => ForumHelper::roleStyle($user_post->role_id),
                    'user_DATA' => json_decode($post->user->DATA, false),
                    'like' => null,
                    'postEdit' => null,
                    'postModer' => null,
                ]);
            }
        }
    }

    private static function visitTopic($topic, $user_role, $user)
    {
        $visit = true;
        $user_id = 0;
        if (!is_null($user)) $user_id = $user->id;

        if ($user_role < 2 && $topic->hide) $visit = false;
        if ($topic->forum->section_id == 3 && $user_role >= 1 && $user_role < 8 && $topic->hide) $visit = false;
        if ($topic->forum_id == 1 && $user_role >= 1 && $user_role < 11 && $topic->hide) $visit = false;
        if ($topic->forum_id == 3 && $user_role >= 1 && $user_role < 11 && $topic->hide) $visit = false;
        if ($topic->forum_id == 2 && $user_role >= 1 && $user_role < 9 && $topic->hide) $visit = false;
        if ($topic->user_id == $user_id && $user_role >= 1 && $topic->moderation) $visit = true;

        return $visit;
    }

    private static function visPost($post, $user_role, $user, $user_post, $model)
    {
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
            'user_role' => Role::find($user_post->role_id)->description,
            'user_role_style' => ForumHelper::roleStyle($user_post->role_id),
            'user_DATA' => json_decode($post->user->DATA, false),
            'like' => Like::select('action')->where([['post_id', $post->id], ['user_id', $user->id]])->first(),
            'postEdit' => ModerHelper::moderPostEdit($user_role, $user, $post->user_id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum->id, $post->topic->forum->section_id, $post->topic_id),
            'postModer' => ModerHelper::moderPost($user_role, $post->topic->forum_id, $post->topic->forum->section_id, $user, $post->topic_id)
        ]);
    }
}
