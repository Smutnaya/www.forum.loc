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
            $table->unsignedInteger('datatime');
            $table->boolean('moderation')->default(false);
            $table->boolean('block')->default(false);
            $table->boolean('hide')->default(false);
            $table->boolean('pin')->default(false);
            $table->json('DATA')->nullable();
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
