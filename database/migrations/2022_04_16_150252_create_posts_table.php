<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->longtext('text');
            $table->longtext('ip')->nullable();
            $table->unsignedInteger('datetime');
            $table->boolean('moderation')->default(false);
            $table->boolean('hide')->default(false);
            $table->integer('like')->default(0);
            $table->integer('dislike')->default(0);
            //$table->boolean('private')->default(false);
            $table->json('DATA')->default('{"user_name_edit":null,"date_edit":null,"first_edit":null,"user_name_moder":null,"date_moder":null,"first":null,"like":0,"like_name":null,"dislike":0,"dislike_name":null,"comment":0}');
            $table->unsignedBigInteger('topic_id')->index();
            $table->unsignedBigInteger('forum_id')->index();
            $table->unsignedBigInteger('user_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
