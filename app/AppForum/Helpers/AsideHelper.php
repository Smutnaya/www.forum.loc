<?php

namespace App\AppForum\Helpers;

use App\Forum;
use App\Topic;
use App\Section;
use App\Other_role;
use PhpParser\Node\Stmt\For_;

class AsideHelper
{
    public static function sectionAside($user)
    {
        $sectionsAside = collect();

        $sections = Section::all();
        if (is_null($sections)) return $sectionsAside;

        if (is_null($user)) return $sectionsAside = Section::where('private', false)->get();

        $user_role = ModerHelper::user_role($user);
        $other_roles = Other_role::where('user_id', $user->id)->get();
        self::setSectionAside($sectionsAside, $sections, $user_role, $other_roles, $user);

        return $sectionsAside;
    }

    private static function setSectionAside($sectionsAside, $sections, $user_role, $other_roles, $user)
    {
        $id_section = collect();
        foreach ($sections as $section) {
            if ($section->private == true && $user_role > 4) {
                ModerHelper::collectionContains($id_section, $section, $sectionsAside);
            }
            if ($section->private == false) {
                if (!$id_section->contains($section->id)) {
                    $sectionsAside->push($section);
                    $id_section->push($section->id);
                }
            }
            if (!is_null($other_roles)) {
                foreach ($other_roles as $other_role) {
                    if ($other_role->section_id != null && $other_role->section_id == $section->id) {
                        ModerHelper::collectionContains($id_section, $section, $sectionsAside);
                    }
                    if ($other_role->forum_id != null) {
                        $forum = Forum::find($other_role->forum_id);
                        if (!is_null($forum) && $forum->section_id == $section->id && $section->id == 7) {
                            ModerHelper::collectionContains($id_section, $section, $sectionsAside);
                        }
                    }
                    if ($other_role->topic_id != null) {
                        $topic = Topic::find($other_role->topic_id);
                        if (!is_null($topic) && $topic->forum->section_id == $section->id && $section->id == 7) {
                            ModerHelper::collectionContains($id_section, $section, $sectionsAside);
                        }
                    }
                }
            }
        }
        return $sectionsAside;
    }
}
