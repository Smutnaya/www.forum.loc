<?php

namespace App\AppForum\Helpers;

use App\Complaint;

class ComplaintHelper
{
    public static $result = false;

    public static function review()
    {
        $complaints = collect();
        $complaints_review = Complaint::where('close', false)->get();
        if ($complaints_review->count() > 0) self::setReview($complaints, $complaints_review);

        return $complaints;
    }

    private static function setReview($complaints, $complaints_review)
    {
        foreach ($complaints_review as $review) {
            $complaints->push([
                'id' => $review->id,
                'datetime' => ForumHelper::timeFormat($review->datetime),
                'ip' => $review->ip,
                'user_id' => $review->user_id,
                'user_name' => $review->user->name,
                'post_id' => $review->post_id,
                'post_datetime' => ForumHelper::timeFormat($review->post->datetime),
                'post_user_id' => $review->post->user_id,
                'post_user_name' => $review->post->user->name,
                'topic_id' => $review->post->topic_id,
                'topic_title' => $review->post->topic->title,
                'forum_id' => $review->post->topic->forum_id,
                'forum_title' => $review->post->topic->forum->title,
                'DATA' => json_decode($review->DATA, false),
            ]);
        }
    }
}
