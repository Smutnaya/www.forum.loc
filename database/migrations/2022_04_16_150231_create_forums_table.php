<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->id(); // integer big unsigned
            //$table->timestamps();
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('moderation')->default(false);
            $table->boolean('hide')->default(false);
            $table->json('DATA')->nullable();
            $table->unsignedBigInteger('section_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forums');
    }
}
