<?php

namespace App\AppForum\Viewers;

use App\Like;
use App\Post;
use App\Role;
use App\User;
use App\Forum;
use App\Topic;
use App\Comment;
use App\Message;
use App\Other_role;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\ComplaintHelper;
use App\AppForum\Helpers\ClanAllianceHelper;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class TopicViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'user' => null,
            'message_new' => null,
            'userBan' => false,
            'forum' => null,
            'section' => null,
            'topic' => null,
            'topicEdit' => false,
            'topicMove' => false,
            'posts' => collect(),
            'comments' => collect(),
            'postsModer' => collect(),
            'sectionsAside' => collect(),
            'visit_forum' => false,
            'newPost' => null,
            'moder' => false,
            'editor' => false,
            'first_post' => null,
            'section_id' => null,
            'forum_id' => null,
            'user_clan' => false,
            'user_clan_moder' => false,
            'user_alliance' => false,
            'user_alliance_moder' => false,
            'complaints' => collect(),

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

        if (!is_null($user)) {
            $model['user'] = $user;
            $mes = Message::where([['user_id_to',  $user->id], ['hide', false], ['view', false]])->get();
            $model['message_new'] = $mes->count();
        }
        $user_role = ModerHelper::user_role($user);

        $topic = Topic::find(intval($topicId)); // eloquent
        if (is_null($topic)) return $model;

        $first_post = Post::where('topic_id', $topic->id)->first();
        $model['first_post'] = $first_post->id;

        self::setTopic($model, $topic, $user_role);

        $model['forum'] = $topic->forum;

        if (!is_null($user)) {
            $section = ModerHelper::getSection($user);

            $model['user_clan'] = ClanAllianceHelper::userClan($user, $topic->forum);
            $model['user_clan_moder'] = ClanAllianceHelper::userClanModer($user, $topic->forum);
            $model['user_alliance'] = ClanAllianceHelper::userAlliance($user, $topic->forum);
            $model['user_alliance_moder'] = ClanAllianceHelper::userAllianceModer($user, $topic->forum);
            $model['complaints'] = ComplaintHelper::review();
        } else $section = null;

        if (!is_null($section)) $model['section'] = $section;

        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlTopic(intval($topicId));

        $topicPage = ForumHelper::topicPage(intval($topicId), $user_role);
        $pages = $topicPage['pages'];
        $take = $topicPage['take'];
        $page = ForumHelper::parsePage($page, $pages);
        $skip = ($page - 1) * $take;

        $posts = self::getPost(intval($topicId), $skip, $take, $user_role, $topic->forum_id, $topic->forum->section_id, $user);
        if ($posts->isEmpty() || $posts->count() < 1) return $model; // !!!!!!!!!!
        self::setPost($model, $posts, $user, $user_role, $topic->forum_id, $topic->forum->section_id);

        $model['newPost'] = ModerHelper::moderPost($user_role, $topic->forum_id, $topic->forum->section_id, $user, $topic->id);
        $model['userBan'] = ModerHelper::banTopic($user, $topic);
        if (!is_null($user) && !is_null($user->newspaper_id) && $user->newspaper->forum_id == $topic->forum_id) {
            $model['editor'] = true;
        }

        $model['pagination']['topicId'] = $topic->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        if (!is_null($user)) {
            $model['moder'] = ModerHelper::moderForum($user_role, $topic->forum_id, $topic->forum->section_id, $user);
            $model['topicEdit'] = ModerHelper::moderTopicEdit($model['user']['role_id'], $model['user']['id'], $model['topic']['datetime_d'], $model['topic']['DATA'], $model['topic']['user_id'], $model['topic']['forum_id'], $model['topic']['section_id'], $model['topic']['id']);
            $model['topicMove'] = ModerHelper::moderTopicMove($model['user']['role_id'], $model['topic']['forum_id'], $model['topic']['section_id'], $user, $model['topic']['id']);

            $other_roles = Other_role::where([['user_id', $user->id], ['moderation', true]])->get();

            if (!$model['moder'] && $other_roles->count() > 0) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $topic->forum->section_id && $other_role->moderation == true) $model['moder'] = true;
                    if ($other_role->forum_id != null && $other_role->forum_id == $topic->forum_id && $other_role->moderation == true) $model['moder'] = true;
                    if ($other_role->topic_id != null && $other_role->topic_id == $topic->id && $other_role->moderation == true) $model['moder'] = true;
                }
            }
        }

        //dd(self::visitTopic($topic, $user_role, $user, $model['moder']));

        if (ModerHelper::visForum($user_role, $topic->forum_id, $topic->forum->section_id, $user) && self::visitTopic($topic, $user_role, $user, $model['moder'])) $model['visit_forum'] = true;

        if ($topic->forum->section_id == 6 && $topic->forum_id != 53) {
            $comments = Comment::where('topic_id', $topic->id)->orderByDesc('datetime')->get();
            if ($comments->count() > 0) self::setComment($comments, $model);
        }
        $model['section_id'] = $topic->forum->section_id;
        $model['forum_id'] = $topic->forum_id;
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

    private static function setComment($comments, $model)
    {
        foreach ($comments as $comment) {
            $model['comments']->push([
                'id' => $comment->id,
                'text' => $comment->text,
                'date' => ForumHelper::timeFormat($comment->datetime),
                'ip' => $comment->ip,
                'DATA' => json_decode($comment->DATA, false),
                'topic_id' => $comment->topic_id,
                'user_id' => $comment->user_id,
                'user_name' => $comment->user->name,
                'user_avatar' => $comment->user->avatar,
            ]);
        }
    }

    public static function getPost($topicId, $skip = null, $take = null, $user_role, $forum_id, $section_id, $user)
    {
        $posts = collect();
        $forum = Forum::find(intval($forum_id));

        if ($user_role == 0 && !is_null($skip)) {
            $posts = Post::where([['topic_id', intval($topicId)], ['moderation', false], ['hide', false]])->skip($skip)->take($take)->get();
        } elseif ($user_role == 0 && is_null($skip)) {
            $posts = Post::where([['topic_id', intval($topicId)], ['moderation', false], ['hide', false]])->get();
        }

        if ($section_id == 3) {
            if ($user_role > 0 && $user_role < 8 && !is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role > 0 && $user_role < 8 && is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
            }

            if ($user_role > 7 && !is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
            } elseif ($user_role > 7 && is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->get();
            }
        } else {
            if ($user_role == 1 && !is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role == 1 && is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
            }
            if ($user_role > 1 && !is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
            } elseif ($user_role > 1 && is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->get();
            }
        }

        if ($forum_id == 1 || $forum_id == 3) {
            if ($user_role >= 1 && $user_role < 11 && !is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role >= 1 && $user_role < 11 && is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
            }
            if ($user_role > 10 && !is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
            } elseif ($user_role > 10 && is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->get();
            }
        }
        if ($forum_id == 2) {
            if ($user_role >= 1 && $user_role < 9 && !is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
            } elseif ($user_role >= 1 && $user_role < 9 && is_null($skip)) {
                $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
            }

            if ($user_role > 8 && !is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
            } elseif ($user_role > 8 && is_null($skip)) {
                $posts = Post::where('topic_id', intval($topicId))->get();
            }
        }

        if ($section_id == 6) {
            if ($user_role >= 8 || $user_role == 4 && !is_null($skip)) $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
            if ($user_role >= 8 || $user_role == 4 && is_null($skip)) $posts = Post::where('topic_id', intval($topicId))->get();

            if ($user_role < 8 && $user_role != 4 && !is_null($skip)) {
                if ($section_id == 6 && !is_null($user) && !is_null($user->newspaper_id) && $user->newspaper->forum_id == $forum_id) {
                    $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
                } else {
                    $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
                }
            }
            if ($user_role < 8 && $user_role != 4 && is_null($skip)) {
                if ($section_id == 6 && !is_null($user) && !is_null($user->newspaper_id) && $user->newspaper->forum_id == $forum_id) {
                    $posts = Post::where('topic_id', intval($topicId))->get();
                } else {
                    $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
                }
            }
        }

        if ($section_id == 5) {
            if ($user_role == 12 && !is_null($skip)) $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
            if ($user_role == 12 && is_null($skip)) $posts = Post::where('topic_id', intval($topicId))->get();
            if ($forum_id == 52 && !is_null($skip)) {
                if ($user_role > 7 || $user_role == 4) {
                    $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
                } else {
                    $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
                }
            }

            if ($forum_id == 52 && is_null($skip)) {
                if ($user_role > 7 || $user_role == 4) {
                    $posts = Post::where('topic_id', intval($topicId))->get();
                } else {
                    $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
                }
            }

            if ($forum_id != 52 && !is_null($skip)) {
                if (ClanAllianceHelper::userAllianceModer($user, $forum) || ClanAllianceHelper::userClanModer($user, $forum)) {
                    $posts = Post::where('topic_id', intval($topicId))->skip($skip)->take($take)->get();
                } else {
                    $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->skip($skip)->take($take)->get();
                }
            }

            if ($forum_id != 52 && is_null($skip)) {

                if (ClanAllianceHelper::userAllianceModer($user, $forum) || ClanAllianceHelper::userClanModer($user, $forum)) {
                    $posts = Post::where('topic_id', intval($topicId))->get();
                } else {
                    $posts = Post::where([['topic_id', intval($topicId)], ['hide', false]])->get();
                }
            }
        }

        if (!is_null($user)) {
            $other_roles = Other_role::where([['user_id', $user->id], ['moderation', true]])->get();

            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $section_id && $other_role->moderation == true) $posts = Post::where('topic_id', intval($topicId))->get();
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum_id && $other_role->moderation == true) $posts = Post::where('topic_id', intval($topicId))->get();
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->id == $topicId && $other_role->moderation == true) $posts = Post::where('topic_id', intval($topicId))->get();
                    }
                }
            }
        }

        return $posts;
    }

    private static function setTopic($model, $topic, $user_role)
    {
        if (!is_null($topic->news_id)) {
            $model['topic'] = [
                'title' => $topic->title,
                'hide' => $topic->hide,
                'block' => $topic->block,
                'pin' => $topic->pin,
                'private' => $topic->private,
                'moderation' => $topic->moderation,
                'id' => $topic->id,
                'datetime' => ForumHelper::timeFormat($topic->datetime),
                'datetime_d' => $topic->datetime,
                'DATA' => json_decode($topic->DATA, false),
                'user_id' => $topic->user_id,
                'avatar' => $topic->user->avatar,
                'news_id' => $topic->news_id,
                'news_title' => $topic->news->title,
                'forum_id' => $topic->forum_id,
                'section_id' => $topic->forum->section_id,
            ];
        } else {
            $model['topic'] = [
                'title' => $topic->title,
                'hide' => $topic->hide,
                'block' => $topic->block,
                'pin' => $topic->pin,
                'private' => $topic->private,
                'moderation' => $topic->moderation,
                'id' => $topic->id,
                'datetime' => ForumHelper::timeFormat($topic->datetime),
                'datetime_d' => $topic->datetime,
                'DATA' => json_decode($topic->DATA, false),
                'user_id' => $topic->user_id,
                'avatar' => $topic->user->avatar,
                'news_id' => null,
                'news_title' => null,
                'forum_id' => $topic->forum_id,
                'section_id' => $topic->forum->section_id,
            ];
        }
    }

    private static function setPost($model, $posts, $user, $user_role, $forum_id, $section_id)
    {
        $forum = Forum::find(intval($forum_id));
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
                    } elseif ($section_id == 6) {
                        if ($user_role > 0 && $user_role < 8 && $post->user_id == $user->id) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        } elseif ($user->newspaper_id != null && $user->newspaper->forum_id == $post->topic->forum_id) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        }
                    }elseif ($section_id == 5) {
                        if ($post->user_id == $user->id) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        } elseif (ClanAllianceHelper::userAllianceModer($user, $forum) || ClanAllianceHelper::userClanModer($user, $forum)) {
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
                            if ($user_role >= 1 && $user_role < 12 || $post->user_id == $user->id) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            } elseif ($user_role > 11) {
                                self::visPost($post, $user_role, $user, $user_post, $model);
                            }
                        } elseif ($user_role > 1) {
                            self::visPost($post, $user_role, $user, $user_post, $model);
                        }
                        if (!is_null($user)) {
                            $other_roles = Other_role::where([['user_id', $user->id], ['moderation', true]])->get();

                            if (!is_null($other_roles) && $post->moderation  && $user->id != $post->user_id) {
                                foreach ($other_roles as $other_role) {
                                    if ($other_role->section_id != null && $other_role->section_id == $section_id && $other_role->moderation == true) self::visPost($post, $user_role, $user, $user_post, $model);
                                    if ($other_role->forum_id != null && $other_role->forum_id == $forum_id && $other_role->moderation == true) self::visPost($post, $user_role, $user, $user_post, $model);
                                    if ($other_role->topic_id != null) {
                                        $topic = Topic::find($other_role->topic_id);
                                        if ($topic->id == $post->topic_id && $other_role->moderation == true) self::visPost($post, $user_role, $user, $user_post, $model);
                                    }
                                }
                            }
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
                    'avatar' => $post->user->avatar,
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

    public static function visitTopic($topic, $user_role, $user, $moder)
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
        if ($topic->forum->section_id == 6 && !is_null($user) && !is_null($user->newspaper_id) && $user->newspaper->forum_id == $topic->forum_id ||  $user_role >= 7) $visit = true;
        if ($topic->forum->section_id == 5 && $topic->forum_id != 52  && $user_role < 12) {
            if ($topic->hide) {
                if (!ClanAllianceHelper::userAllianceModer($user, $topic->forum) && !ClanAllianceHelper::userClanModer($user, $topic->forum)) $visit = false;
            }
            if (!$topic->hide) {
                if (!$topic->private) $visit = true;
                if ($topic->private && !ClanAllianceHelper::userAllianceModer($user, $topic->forum) && !ClanAllianceHelper::userClanModer($user, $topic->forum)) $visit = false;
                if ($topic->private && !ClanAllianceHelper::userAlliance($user, $topic->forum) && !ClanAllianceHelper::userClan($user, $topic->forum)) $visit = false;
            }
        }
        if ($topic->forum->section_id == 7 && $user_role < 8 && $topic->hide && !$moder) $visit = false;

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
            'avatar' => $post->user->avatar,
            'user_role' => Role::find($user_post->role_id)->description,
            'user_role_style' => ForumHelper::roleStyle($user_post->role_id),
            'user_DATA' => json_decode($post->user->DATA, false),
            'like' => Like::select('action')->where([['post_id', $post->id], ['user_id', $user->id]])->first(),
            'postEdit' => ModerHelper::moderPostEdit($user_role, $user, $user->id, $post->datetime, json_decode($post->DATA, false), $post->user_id, $post->topic->forum->id, $post->topic->forum->section_id, $post->topic_id),
            'postModer' => ModerHelper::moderPost($user_role, $post->topic->forum_id, $post->topic->forum->section_id, $user, $post->topic_id)
        ]);
    }
}
