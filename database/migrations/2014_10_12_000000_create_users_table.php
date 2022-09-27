<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('ip');
            $table->string('avatar')->nullable();
            $table->unsignedInteger('id_clan')->nullable();
            $table->unsignedInteger('id_alians')->nullable();
            $table->unsignedInteger('level')->nullable();
            $table->boolean('ban_message')->default(false);
            $table->rememberToken();
            $table->json('DATA')->default('{"post_count":0,"like":0}');
            $table->unsignedBigInteger('role_id')->default('1');
            $table->unsignedBigInteger('newspaper_id')->nullable();
            $table->unsignedInteger('newspaper_role')->nullable();
            $table->unsignedInteger('clan_id')->nullable();
            $table->unsignedInteger('clan_role')->nullable();
            $table->unsignedInteger('alliance_id')->nullable();
            $table->boolean('speaker')->default(false);
            $table->unsignedInteger('action_time')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
