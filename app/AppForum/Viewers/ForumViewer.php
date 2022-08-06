<?php

namespace App\AppForum\Viewers;

use App\Forum;
use App\Topic;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\BreadcrumHtmlHelper;
use App\AppForum\Helpers\ModerHelper;
use App\Post;
use App\Section;

class ForumViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'forumTitle' => null,
            'user' => null,
            'topics' => collect(),
            'posts' => collect(),
            'forumId' => null,
            'visForum' => null,
            'newPost' => null,
            'sections' => collect(),
            'sectionsAside' => collect(),

            'pagination' => collect([
                'page' => null,
                'pages' => null,
                'forumId' => null,
            ]),
        ]);
    }

    public static function index($forumId, $user, $page)
    {
        $model = self::init();

        if (!is_null($user)) $model['user'] = $user;
        $user_role = ModerHelper::user_role($user);

        // section
        if (is_null($user) || $user->role_id < 5) {
            $sectionsAside = Section::where('private', false)->get();
        } else {
            $sectionsAside = Section::all();
        }
        MainViewer::setSectionAside($model, $sectionsAside);

        $forum = Forum::find(intval($forumId));

        if (is_null($forum)) return $model;
        $model['forumTitle'] = $forum->title;
        $model['forumId'] = $forum->id;
        $model['forumBlock'] = $forum->block;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum(intval($forumId));

        $model['visForum'] = ModerHelper::visForum($user_role, $forum->id, $forum->section_id);

        if ($user_role < 1) {
            $post_num = Topic::where([['forum_id', intval($forumId)], ['moderation', false], ['hide', false]])->count();
        } elseif ($user_role == 1) {
            $post_num = Topic::where([['forum_id', intval($forumId)], ['moderation', false]])->count();
        } else {
            $post_num = Topic::where('forum_id', intval($forumId))->count();
        }

        $take = 30;
        $pages = (int) ceil($post_num / $take);
        $page = ForumHelper::parsePage($page, $pages);
        $skip = ($page - 1) * $take;

        $topics = self::getTopic(intval($forumId), $skip, $take, $user_role, $forum->section_id);
        if ($topics->isEmpty()) return $model;

        self::setTopic($model, $topics, $user);
        $model['newPost'] = ModerHelper::moderPost($user_role, $forum->id, $forum->section_id);

        $model['pagination']['forumId'] = $forum->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        return $model;
    }

    public static function getTopic($forum_id, $skip = null, $take = null, $user_role, $section_id)
    {
        $topics = collect();

        if ($user_role == 0 && !is_null($skip)) {
            $topics = Topic::where([['forum_id', $forum_id], ['moderation', false], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
        } elseif ($user_role == 0 && is_null($skip)) {
            $topics = Topic::where([['forum_id', $forum_id], ['moderation', false], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
        }


        if ($user_role > 0 && !is_null($skip)) {
            if ($section_id == 1) {
                if ($forum_id == 1) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 3) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 2) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 16) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 17) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 15) {
                    if ($user_role == 4 || $user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if (!$forum_id == 1 && !$forum_id == 3 && !$forum_id == 16 && !$forum_id == 17 && !$forum_id == 15 && $user_role > 1) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                }
            }
            if ($section_id == 2) {
                if ($forum_id == 40) {
                    if ($user_role == 4 || $user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                    if ($forum_id == 39) {
                        if ($user_role > 1) {
                            return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                        } else {
                            return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                        }
                    }
                    if (!$forum_id == 40 && $user_role > 1) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
            }

            if ($section_id == 3) {
                if ($forum_id == 41 || $forum_id == 42 || $forum_id == 43 || $forum_id == 44 || $forum_id == 45 || $forum_id == 46 || $forum_id == 47 || $forum_id == 51) {
                    if ($user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 48 || $forum_id == 49 || $forum_id == 50) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
            }

            if ($section_id == 4) {
                return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
            } else {
                return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
            }

            if ($section_id == 5) {
                if ($user_role > 10) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                }
            }
            if ($section_id == 6) {
                if ($user_role > 7) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                }
            }
            if ($section_id == 7) {
                if ($forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 65 || $forum_id == 66 || $forum_id == 71) {
                    if ($user_role > 5) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 62 || $forum_id == 63 || $forum_id == 64) {
                    if ($user_role > 6) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 54 || $forum_id == 55 || $forum_id == 56 || $forum_id == 57 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69) {
                    if ($user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 70) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->skip($skip)->take($take)->get();
                    }
                }
            }
        } elseif ($user_role > 0 && is_null($skip)) {
            if ($section_id == 1) {
                if ($forum_id == 1) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 3) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 2) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 16) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 17) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 15) {
                    if ($user_role == 4 || $user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if (!$forum_id == 1 && !$forum_id == 3 && !$forum_id == 16 && !$forum_id == 17 && !$forum_id == 15 && $user_role > 1) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                }
            }
            if ($section_id == 2) {
                if ($forum_id == 40) {
                    if ($user_role == 4 || $user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                    if ($forum_id == 39) {
                        if ($user_role > 1) {
                            return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                        } else {
                            return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                        }
                    }
                    if (!$forum_id == 40 && $user_role > 1) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
            }

            if ($section_id == 3) {
                if ($forum_id == 41 || $forum_id == 42 || $forum_id == 43 || $forum_id == 44 || $forum_id == 45 || $forum_id == 46 || $forum_id == 47 || $forum_id == 51) {
                    if ($user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 48 || $forum_id == 49 || $forum_id == 50) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
            }

            if ($section_id == 4) {
                return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
            } else {
                return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
            }

            if ($section_id == 5) {
                if ($user_role > 10) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                }
            }
            if ($section_id == 6) {
                if ($user_role > 7) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                }
            }
            if ($section_id == 7) {
                if ($forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 65 || $forum_id == 66 || $forum_id == 71) {
                    if ($user_role > 5) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 62 || $forum_id == 63 || $forum_id == 64) {
                    if ($user_role > 6) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 54 || $forum_id == 55 || $forum_id == 56 || $forum_id == 57 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69) {
                    if ($user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
                if ($forum_id == 70) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('id')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('id')->get();
                    }
                }
            }
        }

        return $topics;
    }

    public static function topic($forumId, $user)
    {
        $model = self::init();

        // section
        if (is_null($user) || $user->role_id < 5) {
            $sectionsAside = Section::where('private', false)->get();
        } else {
            $sectionsAside = Section::all();
        }
        MainViewer::setSectionAside($model, $sectionsAside);

        if (!is_null($user)) $model['user'] = $user;

        $forum = Forum::find(intval($forumId));
        if (is_null($forum)) return $model;
        $model['forumTitle'] = $forum->title;
        $model['sections']['moderation'] = null;
        $model['sections']['hide'] = null;
        if ($forum->section->moderation || $forum->moderation) $model['sections']['moderation'] = true;
        if ($forum->section->hide || $forum->hide) $model['sections']['hide'] = true;
        $model['sections']['id'] = $forum->section->id;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum(intval($forumId));
        return $model;
    }

    private static function setTopic($model, $topics, $user)
    {

        foreach ($topics as $topic) {
            //if (!is_null($user)) { //$topic->moderation && $topic->$user_id == $user_id && $user_role == 1 ||
            $model['topics']->push([
                'id' => $topic->id,
                'title' => $topic->title,
                'title_slug' => ForumHelper::slugify($topic->title),
                'datetime' => ForumHelper::timeFormat($topic->datetime),
                'hide' => $topic->hide,
                'block' => $topic->block,
                'pin' => $topic->pin,
                'moderation' => $topic->moderation,
                'DATA' => json_decode($topic->DATA, false),
                'forum_id' => $topic->forum_id,
                'user_id' => $topic->user_id,
                'user' => $topic->user->name,
            ]);
        }
        //}
    }
}
