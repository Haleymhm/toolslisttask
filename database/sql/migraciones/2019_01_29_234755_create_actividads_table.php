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
        Schema::create('actividads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('empresauid',36);
            $table->char('useruid',36);
            $table->string('unidadopuid',36)->nullable();
            $table->string('tipoactividaduid',36)->nullable();
            $table->text('actividadtitulo');
            $table->text('actividaddescip');
            $table->date('actividainicio');
            $table->date('actividadfin');
            $table->text('actividadlugar');
            $table->string('actividadcolor',8)->nullable();
            $table->char('actividadstatus',1)->nullable()->default("A");
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
        Schema::dropIfExists('actividads');
    }
}
