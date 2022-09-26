<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->longtext('text');
            $table->longtext('ip')->nullable();
            $table->unsignedInteger('datetime');
            //$table->boolean('moderation')->default(false);
            //$table->boolean('hide')->default(false);
            $table->json('DATA')->default('{"user_name_edit":null,"user_id_edit":null,"date_edit":null,"like":0,"like_name":null,"dislike":0,"dislike_name":null}');
            $table->unsignedBigInteger('topic_id')->index();
            $table->unsignedBigInteger('post_id')->nullable();
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
        Schema::dropIfExists('comments');
    }
}
