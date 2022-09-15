<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_id_to');
            $table->string('title');
            $table->longtext('text');
            $table->unsignedInteger('datetime');
            $table->boolean('view')->default(false);
            $table->unsignedBigInteger('reply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
