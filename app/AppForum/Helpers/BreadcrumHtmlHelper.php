<?php

namespace App\AppForum\Helpers;

use App\Forum;
use App\Topic;
use App\Section;

class BreadcrumHtmlHelper
{
    public static function breadcrumpHtmlForum($forumId)
    {
        $forum = Forum::find(intval($forumId));

        $breadcrumpForum = [];

        $inf =[
            'title' => 'Главная',
            'url' => '/main',
        ];
        array_push($breadcrumpForum, $inf);

        $allF =[
            'title' => 'Форумы',
            'url' => '/fs',
        ];
        array_push($breadcrumpForum, $allF);

        $sectionInf = [
            'title' => $forum->section->title,
            'url' => '/s/'.$forum->section->id,
        ];
        array_push($breadcrumpForum, $sectionInf);

        $forumInf = [
            'title' => $forum->title,
            'url' => null,
        ];
        array_push($breadcrumpForum, $forumInf);

        $html =  self::breadcrumpHtml($breadcrumpForum);

        //dd($html);

        return $html;
    }

    public static function breadcrumpHtmlSection($sectionId)
    {
        $breadcrumpForum = [];
        $section = Section::find(intval($sectionId));

        $inf =[
            'title' => 'Главная',
            'url' => '/main',
        ];
        array_push($breadcrumpForum, $inf);

        $allF =[
            'title' => 'Форумы',
            'url' => '/fs',
        ];
        array_push($breadcrumpForum, $allF);

        $sectionInf = [
            'title' => $section->title,
            'url' => null,
        ];
        array_push($breadcrumpForum, $sectionInf);

        $html =  self::breadcrumpHtml($breadcrumpForum);
        return $html;
    }

    public static function breadcrumpHtmlTopic($topicId)
    {
        $breadcrumpForum = [];
        $topic = Topic::find(intval($topicId));

        $inf =[
            'title' => 'Главная',
            'url' => '/main',
        ];
        array_push($breadcrumpForum, $inf);

        $allF =[
            'title' => 'Форумы',
            'url' => '/fs',
        ];
        array_push($breadcrumpForum, $allF);

        $sectionInf = [
            'title' => $topic->forum->section->title,
            'url' => '/s/'.$topic->forum->section->id,
        ];
        array_push($breadcrumpForum, $sectionInf);

        $forumInf = [
            'title' => $topic->forum->title,
            'url' => '/f/'.$topic->forum->id,
        ];

        array_push($breadcrumpForum, $forumInf);

        $topicInf = [
            'title' => $topic->title,
            'url' => null,
        ];
        array_push($breadcrumpForum, $topicInf);


        $html =  self::breadcrumpHtml($breadcrumpForum);

        return $html;
    }

    public static function breadcrumpHtml($arr)
    {
        $html = '<nav aria-label="breadcrumb">
        <ol class="breadcrumb">';
        foreach($arr as $a)
        {
            if($a['url'] != null)
            {
                $html .= '<li class="breadcrumb-item"><a href='.$a['url'].'>'.$a['title'].'</a></li>';
            }
            else
            {
                $html .= '<li class="breadcrumb-item active" aria-current="page">'.$a['title'].'</li>';
            }
        }


        $html .= '</ol></nav>';
        return $html;
    }
}
