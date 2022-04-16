<?php

use App\Post;
use App\Forum;
use App\Topic;
use App\Section;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Section::insert([
            ['title' => 'Времена Смуты'],
            ['title' => 'Дополнительный']
        ]);

        Forum::insert([
            ['title'=> 'Perviy', 'section_id' => 1]
        ]);

        Topic::insert([
            ['title' => 'aaa', 'text' => 'textttt', 'datatime' => strtotime('+5 minutes'), 'user_id' => 1, 'forum_id' => 1],
            ['title' => 'bbb', 'text' => 'textttt', 'datatime' => strtotime('+10 hours'), 'user_id' => 1, 'forum_id' => 1],
            ['title' => 'ccc', 'text' => 'textttt', 'datatime' => strtotime('+5 days'), 'user_id' => 1, 'forum_id' => 1]
        ]);

        Post::insert([
            ['text' => 'textttt', 'datatime' => strtotime('+10 minutes'), 'user_id' => 1, 'topic_id' => 1],
            ['text' => 'textttt222', 'datatime' => strtotime('+15 minutes'), 'user_id' => 1, 'topic_id' => 1]
        ]);

    }
}
