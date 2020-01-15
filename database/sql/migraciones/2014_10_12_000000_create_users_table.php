<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


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
            $table->uuid('id')->primary();
            $table->char('uidempresa',36)->nullable();
            $table->char('selectuniop',36)->nullable();
            $table->char('selecttipact',36)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('photo')->nullable();
            $table->string('cargo')->nullable();
            $table->string('timezone')->nullable();
            $table->char('language',2)->nullable()->default("es");
            $table->integer('vista')->default(0);
            $table->string('password');
            $table->char('status',1)->nullable()->default("A");
            $table->rememberToken();
            $table->timestamps();
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
