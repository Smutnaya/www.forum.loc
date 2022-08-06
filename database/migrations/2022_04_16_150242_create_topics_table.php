<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->string('title');
            $table->unsignedInteger('datetime');
            $table->boolean('moderation')->default(false);
            $table->boolean('block')->default(false);
            $table->boolean('hide')->default(false);
            $table->boolean('pin')->default(false);
            $table->boolean('comment')->default(false);
            // $table->boolean('private')->default(false);
            // $table->boolean('admin')->default(false);
            $table->json('DATA')->default('{"last_post":{"user_name":null,"user_id":null,"title":null,"post_id":null,"date":null},"inf":{"post_count":0,"views":0},"moder":null}');
            $table->unsignedBigInteger('forum_id');
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
        Schema::dropIfExists('topics');
    }
}
