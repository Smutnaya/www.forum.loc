<?php

namespace App\AppForum\Managers;

use App\Images;
use Illuminate\Support\Facades\Storage;

class ImagesManager
{
    public static function images_post($url, $user_id, $size)
    {
        Images::create([
            'url' => $url,
            'user_id' => $user_id,
            'size' => $size,
            'datetime' => time(),
        ]);
    }

    public static function post_id($image, $post_id)
    {
        return $image = Images::where('url', $image->url)->update(['post_id' => $post_id]);
    }


    public static function del($image)
    {
        Storage::delete($image->url);
        $image = Images::where('url', $image->url)->delete();

    }
}
