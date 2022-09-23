<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->longtext('text');
            $table->longtext('ip')->nullable();
            $table->unsignedInteger('datetime');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('close')->default(false);
            $table->boolean('approval')->nullable();
            $table->unsignedInteger('close_datetime');
            $table->unsignedBigInteger('user_id_close')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complains');
    }
}
