<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->longtext('ip')->nullable();
            $table->unsignedInteger('datetime');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('close')->default(false)->index();
            $table->json('DATA')->default('{"approval":null,"close_date":null,"user_id_close":null,"user_id_close":null,"user_name_close":null,"close_datetime":null}');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
