<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            //$table->id();
            $table->string('url', 255)->primary();
            //$table->timestamps();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedInteger('datetime');
            $table->unsignedBigInteger('size');
            $table->unsignedBigInteger('post_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
