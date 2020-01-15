<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipoacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('uid',36);
            $table->char('empresauid',36);
            $table->string('titulo');
            $table->text('tipoactdescrip');
            $table->string('tipoactcolor',8)->nullable();
            $table->char('tvista',3)->nullable()->default("cal");
            $table->char('mcal',2)->nullable()->default("SI");
            $table->char('mind',2)->nullable()->default("SI");
            $table->integer('parent')->default(0);
            $table->integer('orden')->default(0);
            $table->char('icono',36)->nullable()->default("fa fa-plus ");
            $table->char('tmenu',1)->nullable()->default("T");
            $table->char('status',1)->nullable()->default("A");
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
        Schema::dropIfExists('tipoacts');
    }
}
