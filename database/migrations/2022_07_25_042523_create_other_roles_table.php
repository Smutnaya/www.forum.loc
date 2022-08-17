<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_roles', function (Blueprint $table) {
            $table->id();
            //$table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('datetime');
            //$table->unsignedInteger('datetime_end');
            $table->boolean('moderation')->default(false);
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('forum_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_roles');
    }
}
