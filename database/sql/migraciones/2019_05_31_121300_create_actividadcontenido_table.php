<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividadcontenido', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('empresauid',36);
            $table->char('uniopuid',36);
            $table->char('tipactuid',36);
            $table->char('actividaduid',36);
            $table->integer('contenidotipoactuid');
            $table->text('valortexto');
            $table->decimal('valornumero',65,2);
            $table->string('valorcarpeta');
            $table->date('valorfecha');
            $table->string('valorlista');
            $table->string('idlista',36)->nullable();
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
        Schema::dropIfExists('actividadcontenido');
    }
}
