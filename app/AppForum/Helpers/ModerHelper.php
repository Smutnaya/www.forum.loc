<?php

namespace App\AppForum\Helpers;

use App\Ban;
use App\User;
use App\Forum;
use App\Topic;
use App\Section;

use App\Other_role;
use function PHPUnit\Framework\isNull;

class ModerHelper
{
    public static $result = false;

    public static function getSection($user)
    {
        $section = null;
        if (!is_null($user)) {
            if ($user->role_id == 2) {
                $section = Section::where([['private', false], ['id', '!=', '3'], ['id', '!=', '5'], ['id', '!=', '6']])->get();
            } elseif ($user->role_id == 3 || $user->role_id > 4 && $user->role_id < 8) {
                $section = Section::where([['private', false], ['id', '!=', '5'], ['id', '!=', '6']])->get();
            } elseif ($user->role_id == 4) {
                $section = Section::where('id', '!=', '7')->get();
            } elseif ($user->role_id >= 8 && $user->role_id <= 10) {
                $section = Section::where([['id', '!=', '5'], ['id', '!=', '6']])->get();
            } elseif ($user->role_id == 11) {
                $section = Section::where('id', '!=', '5')->get();
            } elseif ($user->role_id > 11) {
                $section = Section::all();
            }
        } else  $section = Section::where([['private', false], ['id', '!=', '3'], ['id', '!=', '5'], ['id', '!=', '6']])->get();
        return $section;
    }

    public static function getForum($user, $section_id)
    {
        // $forums = null;
        // if (!is_null($user)) {
        //     if ($user->role_id == 1) {
        //         $forums = Forum::where([['section_id', $section_id], ['private', false], ['section_id', '<', 7]])->get();
        //     }
        //     if ($user->role_id >= 2 && $user->role_id <= 3) {
        //         $forums = Forum::where([['section_id', $section_id], ['section_id', '<', 7], ['id', '!=', 16], ['id', '!=', 17], ['id', '<', 54]])->get();
        //     }
        //     if ($user->role_id == 4) {
        //         $forums = Forum::where([['section_id', $section_id], ['section_id', '!=', '7']])->get();
        //     }
        //     if ($user->role_id >= 5 && $user->role_id < 9) {
        //         $forums = Forum::where([['section_id', $section_id], ['id', '!=', 16], ['id', '!=', 17]])->get();
        //     }
        //     if ($user->role_id >= 9) {
        //         $forums = Forum::where('section_id', $section_id)->get();
        //     }
        // } else $forums = Forum::where([['section_id', $section_id], ['private', false], ['section_id', '<', 7]])->get();
        // return $forums;

        $forums_vis = collect();

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();
        }

        $user_role = ModerHelper::user_role($user);

        if ($user_role == 0) {
            return $forums_vis = Forum::where([['private', false], ['section_id', $section_id]])->get();
        }
        $forums = Forum::where('section_id', $section_id)->get();

        foreach ($forums as $forum) {
            if ($user_role >= 2 && $user_role <= 3 && $forum->section_id != 7) {
                if ($forum->id != 16 && $forum->id != 17) $forums_vis->push($forum);
            } elseif ($user_role == 1 && $forum->private == false && $forum->section_id != 7) {
                $forums_vis->push($forum);
            } elseif ($user_role == 4 && $forum->section_id != 7) {
                if ($forum->id != 16 && $forum->id != 17) $forums_vis->push($forum);
            } elseif ($user_role >= 5 && $user_role <= 8 && $forum->section_id != 7) {
                if ($forum->id != 16 && $forum->id != 17) $forums_vis->push($forum);
            } elseif ($user_role >= 9) {
                $forums_vis->push($forum);
            }
            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $forum->section_id) $forums_vis->push($forum);
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum->id) $forums_vis->push($forum);
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->forum_id == $forum->id) $forums_vis->push($forum);
                    }
                }
            }
        }

        return $forums_vis;
    }

    public static function getForumAll($user_role, $user)
    {
        $forums_vis = collect();

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();
        }

        if ($user_role == 0) {
            return $forums_vis = Forum::where([['private', false], ['section_id', '<', 7]])->get();
        }
        $forums = Forum::all();

        foreach ($forums as $forum) {
            if ($user_role >= 2 && $user_role <= 3 && $forum->section_id != 7) {
                if ($forum->id != 16 && $forum->id != 17) $forums_vis->push($forum);
            } elseif ($user_role == 1 && $forum->private == false && $forum->section_id != 7) {
                $forums_vis->push($forum);
            } elseif ($user_role == 4 && $forum->section_id != 7) {
                if ($forum->id != 16 && $forum->id != 17) $forums_vis->push($forum);
            } elseif ($user_role >= 5 && $user_role <= 8 && $forum->section_id != 7) {
                if ($forum->id != 16 && $forum->id != 17) $forums_vis->push($forum);
            } elseif ($user_role >= 9) {
                $forums_vis->push($forum);
            }
            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $forum->section_id) $forums_vis->push($forum);
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum->id) $forums_vis->push($forum);
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->forum_id == $forum->id) $forums_vis->push($forum);
                    }
                }
            }
        }

        return $forums_vis;
    }

    // видимость форумов
    public static function visForum($user_role, $forum_id, $section_id, $user)
    {
        $vis = true;

        if ($section_id == 6) {
            $vis = true;
        } elseif ($user_role == 4 && $forum_id == 16) {
            $vis = false;
        } elseif ($user_role == 4 && $forum_id == 17) {
            $vis = false;
        } elseif ($user_role > 11) {
            $vis = true;
        } elseif ($forum_id == 52 || $forum_id == 53) {
            $vis = true;
        } else {
            if ($user_role < 9 && ($forum_id == 16 || $forum_id == 17 || $forum_id == 70 || $section_id == 5)) $vis = false;
            if ($user_role < 8 && ($forum_id == 56 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69 || $section_id == 5)) $vis = false;
            if ($user_role < 7 && ($forum_id == 62 || $forum_id == 63 || $forum_id == 64 || $section_id == 5)) $vis = false;
            if ($user_role < 6 && ($forum_id == 54 || $forum_id == 55 || $forum_id == 57 || $forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 66 || $section_id == 5)) $vis = false;
            if ($user_role < 5 && ($forum_id == 65 || $forum_id == 71 || $section_id == 5)) $vis = false;
            if ($user_role < 4 && $section_id == 5) $vis = false;
            if ($user_role < 2 && $forum_id == 40) $vis = false;
            if ($user_role >= 9 &&  $user_role < 12 && $section_id == 5) $vis = false;
        }

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();

            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $section_id) $vis = true;
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum_id) $vis = true;
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->forum_id == $forum_id) $vis = true;
                    }
                }
            }
        }

        return $vis;
    }

    // public static function getTopic($user, $forum_id)
    // {
    //     $forums = null;
    //     if ($user->role_id == 1) {
    //         $forums = Topic::where([['forum_id', $forum_id], ['private', false], ['hide', false], ['section_id', '<', 7]])->get();
    //     }
    //     if ($user->role_id >= 2 && $user->role_id <= 3) {
    //         $forums = Topic::where([['forum_id', $forum_id], ['section_id', '<', 7], ['id', '!=', 16], ['id', '!=', 17], ['id', '<', 54]])->get();
    //     }
    //     if ($user->role_id == 4) {
    //         $forums = Topic::where([['forum_id', $forum_id], ['section_id', '!=', '7']])->get();
    //     }
    //     if ($user->role_id >= 5 && $user->role_id < 9) {
    //         $forums = Topic::where([['forum_id', $forum_id], ['id', '!=', 16], ['id', '!=', 17]])->get();
    //     }
    //     if ($user->role_id >= 9) {
    //         $forums = Topic::where('forum_id', $forum_id)->get();
    //     }
    //     return $forums;
    // }

    //topic

    public static function moderTopicEdit($user_role_id, $user_id, $topic_datetime, $topic_DATA, $topic_user_id, $forum_id, $section_id, $topic_id)
    {
        self::$result = false;
        if ($user_role_id == 1 && $topic_user_id == $user_id && time() <= $topic_datetime + 900 && is_null($topic_DATA->moder)) return self::$result = true;
        //if ($user_role_id > 1 && $topic_user_id == $user_id && time() <= $topic_datetime + 900 && is_null($topic_DATA->moder)) return self::$result = true;

        $user = User::find($user_id);
        $other_roles = Other_role::where('user_id', $user->id)->get();

        if (!is_null($other_roles)) {
            foreach ($other_roles as $other_role) {
                if ($other_role->section_id != null && $other_role->section_id == $section_id) return self::$result = true;
                if ($other_role->forum_id != null && $other_role->forum_id == $forum_id) return self::$result = true;
                if ($other_role->topic_id != null) {
                    $topic = Topic::find($other_role->topic_id);
                    if ($topic->id == $topic_id) return self::$result = true;
                }
            }
        }

        if ($user_role_id == 8 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $forum_id != 69 && $forum_id != 70) return self::$result = true;
        if ($user_role_id == 9 || $user_role_id == 10 || $user_role_id == 11 && ($forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $section_id != 5)) return self::$result = true;
        if ($user_role_id == 2 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $section_id != 3 && $section_id < 5) return self::$result = true;
        if ($user_role_id == 3 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $user_role_id < 8 && $forum_id != 16 && $forum_id != 17 && $section_id < 5) return self::$result = true;
        if ($user_role_id >= 5 && $user_role_id < 8 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $user_role_id < 8 && $forum_id != 16 && $forum_id != 17 && $section_id != 3 && $section_id < 5) return self::$result = true;
        if ($user_role_id == 4 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $section_id != 7) return self::$result = true;
        if ($user_role_id > 10) return self::$result = true;
        return self::$result;
    }

    public static function moderTopicMove($user_role_id, $forum_id, $section_id, $user, $topic_id)
    {
        self::$result = false;

        if ($user_role_id == 8 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $forum_id != 69 && $forum_id != 70 && $section_id != 5) return self::$result = true;
        if ($user_role_id == 9 || $user_role_id == 10 || $user_role_id == 11 && ($forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $section_id != 5)) return self::$result = true;
        if ($user_role_id == 2 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $section_id != 3 && $section_id < 5) return self::$result = true;
        if ($user_role_id == 3 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $user_role_id < 8 && $forum_id != 16 && $forum_id != 17 && $section_id < 5) return self::$result = true;
        if ($user_role_id < 8 && $user_role_id >= 5 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $user_role_id < 8 && $forum_id != 16 && $forum_id != 17 && $section_id != 3 && $section_id < 5) return self::$result = true;
        if ($user_role_id == 4 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $section_id != 7) return self::$result = true;
        if ($user_role_id > 11) return self::$result = true;
        return self::$result;
    }
    public static function moderTopicMoveTo($user_role_id, $forum_id, $section_id, $user, $topic_id)
    {
        self::$result = false;

        if ($user_role_id == 8 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $forum_id != 69 && $forum_id != 70 && $section_id != 5) return self::$result = true;
        if ($user_role_id > 11) return self::$result = true;
        if ($user_role_id == 9 || $user_role_id == 10 || $user_role_id == 11 && ($forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $section_id != 5)) return self::$result = true;
        if ($user_role_id == 2 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $section_id != 3 && $section_id < 5) return self::$result = true;
        if ($user_role_id == 3 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $section_id < 5) return self::$result = true;
        if ($user_role_id >= 5 && $user_role_id < 8 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $forum_id != 16 && $forum_id != 17 && $section_id != 3 && $section_id < 5) return self::$result = true;
        if ($user_role_id == 4 && $forum_id != 1 && $forum_id != 2 && $forum_id != 3 && $section_id != 7) return self::$result = true;
        return self::$result;
    }

    //post

    public static function moderPostEdit($user_role_id, $user, $user_id, $post_datetime, $post_DATA, $post_user_id, $forum_id, $section_id, $topic_id)
    {
        self::$result = false;
        if ($user_role_id == 1 && $post_user_id == $user_id && time() <= $post_datetime + 1800 && is_null($post_DATA->date_moder)) return self::$result = true;
        if ($user_role_id > 1 && $user_role_id < 9 && time() <= $post_datetime + 7200 && is_null($post_DATA->date_moder)) return self::$result = true;
        if ($user_role_id > 8) return self::$result = true;

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();

            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $section_id) return self::$result = true;
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum_id) return self::$result = true;
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->id == $topic_id) return self::$result = true;
                    }
                }
            }
        }

        return self::$result;
    }

    public static function moderPost($user_role_id, $forum_id, $section_id, $user, $topic_id)
    {
        self::$result = false;

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();

            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $section_id) return self::$result = true;
                    if ($other_role->forum_id != null && $other_role->forum_id == $forum_id) return self::$result = true;
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if ($topic->id == $topic_id) return self::$result = true;
                    }
                }
            }
        }

        if ($forum_id == 3 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 1 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 2 && $user_role_id > 8) return self::$result = true;
        if ($forum_id == 16 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 17 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 15 && $user_role_id == 4) return self::$result = true;
        if ($forum_id == 15 && $user_role_id > 17) return self::$result = true;
        if ($forum_id >= 4 && $forum_id < 15 && $user_role_id > 1) return self::$result = true;
        if ($forum_id >= 18 && $forum_id < 39 && $user_role_id > 1) return self::$result = true;

        //$section_id 2
        if ($forum_id == 40 && $user_role_id == 4) return self::$result = true;
        if ($forum_id == 40 && $user_role_id > 4) return self::$result = true;
        if ($forum_id == 39 && $user_role_id > 1) return self::$result = true;
        if (!$forum_id == 40 && $user_role_id > 1) return self::$result = true;
        if ($forum_id >= 18 && $forum_id < 39 && $user_role_id > 1) return self::$result = true;

        //$section_id 3
        if ($forum_id == 41 || $forum_id == 42 || $forum_id == 43 || $forum_id == 44 || $forum_id == 45 || $forum_id == 46 || $forum_id == 47 || $forum_id == 51) {
            if ($user_role_id > 7) return self::$result = true;
        }
        if ($forum_id == 48 || $forum_id == 49 || $forum_id == 50) {
            if ($user_role_id > 8)  return self::$result = true;
        }

        if ($section_id == 4) {
            if (!$user_role_id > 1) return self::$result = true;
        }

        if ($section_id == 5) {
            if ($user_role_id > 11) return self::$result = true;
        }
        if ($section_id == 6) {
            if ($user_role_id > 7) return self::$result = true;
        }

        //$section_id 7
        if ($forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 65 || $forum_id == 66 || $forum_id == 71) {
            if ($user_role_id > 5) return self::$result = true;
        }
        if ($forum_id == 62 || $forum_id == 63 || $forum_id == 64) {
            if ($user_role_id > 6) return self::$result = true;
        }
        if ($forum_id == 54 || $forum_id == 55 || $forum_id == 56 || $forum_id == 57 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69) {
            if ($user_role_id > 7) return self::$result = true;
        }
        if ($forum_id == 70) {
            if ($user_role_id > 8) return self::$result = true;
        }

        return self::$result;
    }

    public static function moderForum($user_role_id, $forum_id, $section_id, $user)
    {
        self::$result = false;

        if (!is_null($user)) {
            $other_roles = Other_role::where('user_id', $user->id)->get();
        }
        if (!is_null($other_roles)) {
            foreach ($other_roles as $other_role) {
                if ($other_role->section_id != null && $other_role->section_id == $section_id) return self::$result = true;
                if ($other_role->forum_id != null && $other_role->forum_id == $forum_id) return self::$result = true;
            }
        }

        if ($forum_id == 3 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 1 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 2 && $user_role_id > 8) return self::$result = true;
        if ($forum_id == 16 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 17 && $user_role_id > 10) return self::$result = true;
        if ($forum_id == 15 && $user_role_id == 4) return self::$result = true;
        if ($forum_id == 15 && $user_role_id > 17) return self::$result = true;
        if ($forum_id >= 4 && $forum_id < 15 && $user_role_id > 1) return self::$result = true;
        if ($forum_id >= 18 && $forum_id < 39 && $user_role_id > 1) return self::$result = true;

        //$section_id 2
        if ($forum_id == 40 && $user_role_id == 4) return self::$result = true;
        if ($forum_id == 40 && $user_role_id > 4) return self::$result = true;
        if ($forum_id == 39 && $user_role_id > 1) return self::$result = true;
        if (!$forum_id == 40 && $user_role_id > 1) return self::$result = true;
        if ($forum_id >= 18 && $forum_id < 39 && $user_role_id > 1) return self::$result = true;

        //$section_id 3
        if ($forum_id == 41 || $forum_id == 42 || $forum_id == 43 || $forum_id == 44 || $forum_id == 45 || $forum_id == 46 || $forum_id == 47 || $forum_id == 51) {
            if ($user_role_id > 7) return self::$result = true;
        }
        if ($forum_id == 48 || $forum_id == 49 || $forum_id == 50) {
            if ($user_role_id > 8)  return self::$result = true;
        }

        if ($section_id == 4) {
            if (!$user_role_id > 1) return self::$result = true;
        }

        if ($section_id == 5) {
            if ($user_role_id > 11) return self::$result = true;
        }
        if ($section_id == 6) {
            if ($user_role_id > 7) return self::$result = true;
        }

        //$section_id 7
        if ($forum_id == 59 || $forum_id == 60 || $forum_id == 61 || $forum_id == 65 || $forum_id == 66 || $forum_id == 71) {
            if ($user_role_id > 5) return self::$result = true;
        }
        if ($forum_id == 62 || $forum_id == 63 || $forum_id == 64) {
            if ($user_role_id > 6) return self::$result = true;
        }
        if ($forum_id == 54 || $forum_id == 55 || $forum_id == 56 || $forum_id == 57 || $forum_id == 58 || $forum_id == 67 || $forum_id == 68 || $forum_id == 69) {
            if ($user_role_id > 7) return self::$result = true;
        }
        if ($forum_id == 70) {
            if ($user_role_id > 8) return self::$result = true;
        }

        return self::$result;
    }

    public static function user_role($user)
    {
        $user_role = 0;
        if (!is_null($user)) $user_role = $user->role_id;
        return $user_role;
    }

    public static function blockSection($user_role, $section)
    {
        self::$result = false;
        if ($user_role == 4 && $section != 3 && $section != 5 && $section != 7) return self::$result = true;
        //if ($user_role == 8 && $section != 5 && $section != 7) return self::$result = true;
        if ($user_role == 9 && $section != 5) return self::$result = true;
        if ($user_role == 10 || $user_role == 11 && $section != 5) return self::$result = true;
        if ($user_role > 11) return self::$result = true;

        return self::$result;
    }

    public static function banForum($user, $forum)
    {
        self::$result = false;
        if (is_null($user)) return self::$result = false;

        $bans = Ban::where([['user_id', $user->id], ['forum_out', true]])->get();
        if ($bans->count() > 0 && !is_null($bans)) {
            foreach ($bans as $ban) {
                if ($ban->datetime_end > time() && $ban->cancel == false) return self::$result = true;
            }
        }

        $bans = Ban::where([['user_id', $user->id], ['section_id', $forum->section_id]])->get();
        if ($bans->count() > 0 && !is_null($bans)) {
            foreach ($bans as $ban) {
                if ($ban->datetime_end > time() && $ban->cancel == false) return self::$result = true;
            }
        }

        $bans = Ban::where([['user_id', $user->id], ['forum_id', $forum->id]])->get();
        if ($bans->count() > 0 && !is_null($bans)) {
            foreach ($bans as $ban) {
                if ($ban->datetime_end > time() && $ban->cancel == false) return self::$result = true;
            }
        }

        return self::$result;
    }

    public static function banTopic($user, $topic)
    {
        self::$result = false;
        if (is_null($user)) return self::$result = false;

        self::$result = self::banForum($user, $topic->forum);

        $bans = Ban::where([['user_id', $user->id], ['topic_id', $topic->id]])->get();
        if ($bans->count() > 0 && !is_null($bans)) {
            foreach ($bans as $ban) {
                if ($ban->datetime_end > time() && $ban->cancel == false) return self::$result = true;
            }
        }

        return self::$result;
    }

    public static function banCancel($user, $ban_id)
    {
        self::$result = false;

        $ban = Ban::find(intval($ban_id));
        if (!is_null($ban)) {
            if ($ban->user_id == $user->id) {
                return self::$result = false;
            }
            if (!is_null($ban->forum_out) && $user->role_id > 10) {
                return self::$result = true;
            }
            if (!is_null($ban->section_id) && ($user->role_id == 4 || $user->role_id > 8)) {
                return self::$result = self::blockSection($user->role_id, $ban->section_id);
            }
            if (!is_null($ban->forum_id)) {
                if ($ban->user_moder_id == $user->id) {
                    return self::$result = true;
                }
                if ($ban->user_moder_id <= $user->id) {
                    return self::$result = self::banForum($user, $ban->forum_id);
                }
            }
            if (!is_null($ban->topic_id)) {
                if ($ban->user_moder_id == $user->id) {
                    return self::$result = true;
                }
                if ($ban->user_moder_id <= $user->id) {
                    return self::$result = self::banTopic($user, $ban->topic_id);
                }
            }
        }

        return self::$result;
    }

    public static function roles($user, $user_inf)
    {
        self::$result = false;
        if (is_null($user)) return self::$result = false;

        if ($user->role_id == 12) return self::$result = true;
        if ($user->id == $user_inf['id']) return self::$result = false;
        if ($user->role_id == 11 && $user->role_id > $user_inf['role_id']) return self::$result = true;
        if ($user->role_id == 4 && $user->role_id > $user_inf['role_id']) return self::$result = true;
        if ($user->role_id == 9 || $user->role_id == 10) {
            if ($user_inf['role_id'] != 4 && $user->role_id > $user_inf['role_id']) return self::$result = true;
        }

        return self::$result;
    }
}
