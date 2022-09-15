<?php

namespace App\AppForum\Viewers;

use App\Post;
use App\Forum;
use App\Topic;
use App\Section;
use App\Other_role;
use App\AppForum\Viewers\MainViewer;
use App\AppForum\Helpers\AsideHelper;
use App\AppForum\Helpers\ForumHelper;
use App\AppForum\Helpers\ModerHelper;
use App\AppForum\Helpers\BreadcrumHtmlHelper;

class ForumViewer
{
    private static function init()
    {
        return collect([
            'breadcrump' => null,
            'forumTitle' => null,
            'user' => null,
            'userBan' => false,
            'topics' => collect(),
            'posts' => collect(),
            'forumId' => null,
            'visForum' => null,
            'moder' => false,
            'sections' => collect(),
            'sectionsAside' => collect(),
            'last_posts' => collect(),
            'new_topics' => collect(),

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

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;


        $user_role = ModerHelper::user_role($user);
        $forum = Forum::find(intval($forumId));

        if (is_null($forum)) return $model;
        $model['forumTitle'] = $forum->title;
        $model['forumId'] = $forum->id;
        $model['forumBlock'] = $forum->block;
        $model['breadcrump'] = BreadcrumHtmlHelper::breadcrumpHtmlForum(intval($forumId));

        $model['visForum'] = ModerHelper::visForum($user_role, $forum->id, $forum->section_id, $user);

        if ($user_role == 0) {
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

        $model['userBan'] = ModerHelper::banForum($user, $forum);
        $model['moder'] = ModerHelper::moderForum($user_role, $forum->id, $forum->section_id, $user);

        $model['pagination']['forumId'] = $forum->id;
        $model['pagination']['page'] = $page;
        $model['pagination']['pages'] = $pages;

        if (!is_null($user)) {
            $model['user'] = $user;
        }

        // последние ответы
        $last_posts = Topic::where('time_post', '!=', 'null')->orderBy('time_post', 'desc')->distinct()->limit(20)->get();
        if ($last_posts->count() > 0) MainViewer::setLastPost($model, $last_posts);
        // новые темы
        $new_topics = Topic::orderBy('datetime', 'desc')->limit(20)->get();
        if ($new_topics->count() > 0) MainViewer::setNewTopic($model, $new_topics);

        $topics = self::getTopic(intval($forumId), $skip, $take, $user_role, $forum->section_id, $user);
        if ($topics->isEmpty()) return $model;
        self::setTopic($model, $topics);

        return $model;
    }

    public static function getTopic($forum_id, $skip = null, $take = null, $user_role, $section_id, $user)
    {
        $topics = collect();

        if ($user_role == 0 && !is_null($skip)) {
            return $topics = Topic::where([['forum_id', $forum_id], ['moderation', false], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
        } elseif ($user_role == 0 && is_null($skip)) {
            return $topics = Topic::where([['forum_id', $forum_id], ['moderation', false], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
        }

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();
            $forum = Forum::find($forum_id);

            if ($other_roles->count() > 0) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $section_id && $other_role->moderation) {
                        $section = Section::find($other_role->section_id);
                        if ($section->id == $forum->section_id) {
                            if (!is_null($skip)) {
                                return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                            } elseif (is_null($skip)) {
                                return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                            }
                        }
                    }
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum_id && $other_role->moderation) {
                        if (!is_null($skip)) {
                            return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                        } elseif (is_null($skip)) {
                            return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                        }
                    }
                    if ($other_role->topic_id != null && $other_role->moderation) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->forum_id == $forum_id) $topics->push(Topic::find($topic->id));
                    }
                }
                //return $topics;
            }
        }

        if ($user_role > 0 && !is_null($skip)) {
            if ($section_id == 1) {
                if ($forum_id == 1) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 3) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 2) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 16) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 17) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 72) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 15) {
                    if ($user_role == 4 || $user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if (!$forum_id == 1 && !$forum_id == 3 && !$forum_id == 16 && !$forum_id == 17 && !$forum_id == 72 && !$forum_id == 15 && $user_role > 1) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                }
            }
            if ($section_id == 2) {
                if ($forum_id == 40) {
                    if ($user_role == 4 || $user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 39) {
                    if ($user_role > 1) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if (!$forum_id == 40 && $user_role > 1) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                }
                if (!$forum_id == 40 && !$forum_id == 39 && $user_role > 1) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                }
            }


            if ($section_id == 3) {
                if ($forum_id == 41 || $forum_id == 42 || $forum_id == 43 || $forum_id == 44 || $forum_id == 45 || $forum_id == 46 || $forum_id == 47 || $forum_id == 51) {
                    if ($user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($forum_id == 48 || $forum_id == 49 || $forum_id == 50) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
            }

            if ($section_id == 4) {
                if ($user_role > 2) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                }
            }

            if ($section_id == 5) {
                if ($user_role > 10) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                }
            }
            if ($section_id == 6) {
                if ($user_role > 7) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                }
            }
            if ($section_id == 7) {
                if ($user_role > 5) {
                    if ($forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 65 || $forum_id == 66 || $forum_id == 71) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($user_role > 6) {
                    if ($forum_id == 62 || $forum_id == 63 || $forum_id == 64) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($user_role > 7) {
                    if ($forum_id == 54 || $forum_id == 55 || $forum_id == 56 || $forum_id == 57 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
                if ($user_role > 8) {
                    if ($forum_id == 70) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->skip($skip)->take($take)->get();
                    }
                }
            }
        } elseif ($user_role > 0 && is_null($skip)) {
            if ($section_id == 1) {
                if ($forum_id == 1) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 3) {
                    if ($user_role > 10) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 2) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 16) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 17) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 72) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 15) {
                    if ($user_role == 4 || $user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if (!$forum_id == 1 && !$forum_id == 3 && !$forum_id == 16 && !$forum_id == 17 && !$forum_id == 72 && !$forum_id == 15 && $user_role > 1) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                }
            }
            if ($section_id == 2) {
                if ($forum_id == 40) {
                    if ($user_role == 4 || $user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                    if ($forum_id == 39) {
                        if ($user_role > 1) {
                            return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                        } else {
                            return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                        }
                    }
                    if (!$forum_id == 40 && !$forum_id == 39 && $user_role > 1) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
            }

            if ($section_id == 3) {
                if ($forum_id == 41 || $forum_id == 42 || $forum_id == 43 || $forum_id == 44 || $forum_id == 45 || $forum_id == 46 || $forum_id == 47 || $forum_id == 51) {
                    if ($user_role > 7) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($forum_id == 48 || $forum_id == 49 || $forum_id == 50) {
                    if ($user_role > 8) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
            }

            if ($section_id == 4) {
                if ($user_role > 2) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                }
            }

            if ($section_id == 5) {
                if ($user_role > 10) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                }
            }
            if ($section_id == 6) {
                if ($user_role > 7) {
                    return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                } else {
                    return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                }
            }
            if ($section_id == 7) {
                if ($user_role > 5) {
                    if ($forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 65 || $forum_id == 66 || $forum_id == 71) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($user_role > 6) {
                    if ($forum_id == 62 || $forum_id == 63 || $forum_id == 64) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($user_role > 7) {
                    if ($forum_id == 54 || $forum_id == 55 || $forum_id == 56 || $forum_id == 57 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
                if ($user_role > 8) {
                    if ($forum_id == 70) {
                        return $topics = Topic::where('forum_id', $forum_id)->orderByDesc('pin')->orderByDesc('time_post')->get();
                    } else {
                        return $topics = Topic::where([['forum_id', $forum_id], ['hide', false]])->orderByDesc('pin')->orderByDesc('time_post')->get();
                    }
                }
            }
        }

        return $topics;
    }

    public static function topic($forumId, $user)
    {
        $model = self::init();

        // aside
        $sectionsAside = AsideHelper::sectionAside($user);
        $model['sectionsAside'] = $sectionsAside;

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

    private static function setTopic($model, $topics)
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
