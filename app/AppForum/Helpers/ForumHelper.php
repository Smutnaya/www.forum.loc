<?php

namespace App\AppForum\Helpers;

use App\Message;
use App\Post;
use function PHPUnit\Framework\isNull;

class ForumHelper
{
    public static function timeFormat($time)
    {
        $date = date('Ymd', $time);
        $dateToday = date('Ymd');
        $dateYesterday = date('Ymd', time()  - (24 * 60 * 60));
        $min_dif = intval((time() - $time) / 60);

        if ($time <= time() && $time > (time() - 1 * 59)) {
            return 'Только что';
        }

        if ($min_dif > 0 && $min_dif < 60) {
            if ($min_dif >= 5 && $min_dif <= 20 || $min_dif >= 25 && $min_dif <= 30 || $min_dif >= 35 && $min_dif <= 40 || $min_dif >= 45 && $min_dif <= 50 || $min_dif >= 55 && $min_dif <= 59) {
                return  $min_dif  . ' минут назад';
            }
            if ($min_dif == 1 || $min_dif == 21 || $min_dif == 31 || $min_dif == 41 || $min_dif == 51) {
                return  $min_dif  . ' минута назад';
            }
            if ($min_dif >= 2 && $min_dif <= 4 || $min_dif >= 22 && $min_dif <= 24 || $min_dif >= 32 && $min_dif <= 34 || $min_dif >= 42 && $min_dif <= 44 || $min_dif >= 52 && $min_dif <= 54) {
                return  $min_dif  . ' минуты назад';
            }
        }

        if ($date == $dateToday) {
            return 'Сегодня в ' . date('H:i', $time);
        }

        if ($date == $dateYesterday) {
            return 'Вчера в ' . date('H:i', $time);
        }

        return date('d.m.Y H:i', $time);
    }

    public static function dHiToInt($d, $H, $i)
    {
        $int = 0;
        if (!is_null($d) || $d > 0) $int += $d * 86400;
        if (!is_null($H) || $H > 0) $int += $H * 3600;
        if (!is_null($i) || $i > 0) $int += $i * 60;

        if ($int > 20 * 31556926) $int = 20 * 31556926;

        return $int;
    }

    public static function getId($value)
    {
        $v = explode('-', $value);

        return (int) $v[0];
    }

    public static function translit($text)
    {
        $text = mb_strtolower($text);

        $cyr = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        ];
        $lat = [
            'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', '', 'y', '', 'e', 'yu', 'ya'
        ];

        return str_replace($cyr, $lat, $text);
    }

    public static function slugify($text)
    {
        $text = self::translit($text);

        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function parsePage($page, $pages)
    {
        if ($page == 'end') {
            return $pages;
        }

        return $page < 1 || $page > $pages ? 1 : $page;
    }

    public static function topicPage($topicId, $user_role)
    {
        $topicPage = collect();
        $post_num = 0;

        if ($user_role == 1) {
            $post_num = Post::where([['topic_id', intval($topicId)], ['hide', false]])->count();
        } elseif ($user_role == 0) {
            $post_num = Post::where([['topic_id', intval($topicId)], ['hide', false], ['moderation', false]])->count();
        } else {
            $post_num = Post::where('topic_id', intval($topicId))->count();
        }

        $take = 10;
        $pages = (int) ceil($post_num / $take);
        $topicPage['take'] = $take;
        $topicPage['pages'] = $pages;

        return $topicPage;
    }

    public static function messagePage($user_id)
    {
        $messagePage = collect();
        $mess_num = 0;

        $mess_num = Message::where('user_id', intval($user_id))->orWhere('user_id_to', intval($user_id))->count();

        $take = 15;
        $pages = (int) ceil($mess_num / $take);
        $messagePage['take'] = $take;
        $messagePage['pages'] = $pages;

        return $messagePage;
    }

    public static function roleStyle($user_role)
    {
        $style = null;

        if ($user_role == 1) $style = 'color: #000000 !important; font-weight: bold !important;';
        if ($user_role == 2) $style = 'color: #006843 !important; font-weight: bold !important;';
        if ($user_role == 3) $style = 'color: #006843 !important; font-weight: bold !important;';
        if ($user_role == 4) $style = 'color: #006843 !important; font-weight: bold !important;';
        if ($user_role > 4 && $user_role < 9) $style = 'color: #00299d !important; font-weight: bold !important;';
        if ($user_role == 9) $style = 'color: #00299d !important; font-weight: bold !important;';
        if ($user_role == 10) $style = 'color: #00299d !important; font-weight: bold !important;';
        if ($user_role >= 11) $style = 'color: #c50000 !important; font-weight: bold !important;';

        return $style;
    }

    public static function timeStr($time)
    {
        $str = null;
        $min = 60;
        $hour = 3600;
        $day = 86400;
        $month = 2629743; //2592000
        $year = 31556926;

        if ($time >= $year) {
            $y = floor($time / $year);
            $time -= $y * $year;
            if ($y == 1)  $str .= $y . ' год ';
            if ($y >= 2 && $y <= 4)  $str .= $y . ' года ';
            if ($y >= 5) $str .= $y . ' лет ';
        }
        if ($time >= $month) {
            $y = floor($time / $month);
            $time -= $y * $month;
            if ($y == 1)  $str .= $y . ' месяц ';
            if ($y >= 2 && $y <= 4)  $str .= $y . ' месяца ';
            if ($y >= 5) $str .= $y . ' месяцев ';
        }
        if ($time >= $day) {
            $y = floor($time / $day);
            $time -= $y * $day;
            if ($y == 1 || $y == 21 || $y == 31)  $str .= $y . ' день ';
            if ($y >= 2 && $y <= 4)  $str .= $y . ' дня ';
            if ($y >= 5 && $y <= 20)  $str .= $y . ' дней ';
            if ($y >= 22 && $y <= 24)  $str .= $y . ' дня ';
            if ($y >= 25 && $y <= 30)  $str .= $y . ' дней ';
        }
        if ($time >= $hour) {
            $y = floor($time / $hour);
            $time -= $y * $hour;
            if ($y == 1 || $y == 21)  $str .= $y . ' час ';
            if ($y >= 2 && $y <= 4)  $str .= $y . ' часа ';
            if ($y >= 5 && $y <= 20)  $str .= $y . ' часов ';
            if ($y >= 22 && $y <= 24)  $str .= $y . ' часа ';
        }
        if ($time >= $min) {
            $y = floor($time / $min);
            $time -= $y * $min;
            if ($y == 1 || $y == 21 || $y == 31 || $y == 41 || $y == 41)  $str .= $y . ' минуту ';
            if ($y >= 2 && $y <= 4)  $str .= $y . ' минуты ';
            if ($y >= 5 && $y <= 20)  $str .= $y . ' минут ';
            if ($y >= 22 && $y <= 24)  $str .= $y . ' минуты ';
            if ($y >= 25 && $y <= 30)  $str .= $y . ' минут ';
            if ($y >= 32 && $y <= 34)  $str .= $y . ' минуты ';
            if ($y >= 35 && $y <= 40)  $str .= $y . ' минут ';
            if ($y >= 42 && $y <= 44)  $str .= $y . ' минуты ';
            if ($y >= 45 && $y <= 50)  $str .= $y . ' минут ';
            if ($y >= 52 && $y <= 54)  $str .= $y . ' минуты ';
            if ($y >= 55 && $y <= 60)  $str .= $y . ' минут ';
        }

        return $str;
    }
}
