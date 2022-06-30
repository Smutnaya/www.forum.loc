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
            $table->unsignedInteger('datatime');
            $table->boolean('moderation')->default(false);
            $table->boolean('hide')->default(false);
            $table->integer('like')->default(0);
            $table->integer('dislike')->default(0);
            $table->json('DATA')->default('{"user_name_moder":null,"date_moder":null,"first":null}');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('user_id');
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
