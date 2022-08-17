<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bans', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->longtext('text');
            $table->unsignedInteger('datetime');
            $table->unsignedInteger('datetime_end');
            $table->longtext('datetime_str');
            $table->boolean('forum_out')->default(false);
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('forum_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('user_moder_id');
            $table->boolean('cancel')->default(false);
            $table->unsignedBigInteger('user_cancel_id')->nullable();
            $table->longtext('text_cancel')->nullable();
            $table->unsignedInteger('datetime_cancel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bans');
    }
}
