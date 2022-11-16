<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbProcedimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_procedimento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');
            $table->string('CO_PROCEDIMENTO')->nullable();
            $table->string('NO_PROCEDIMENTO')->nullable();
            $table->string('TP_COMPLEXIDADE')->nullable();
            $table->string('TP_SEXO')->nullable();
            $table->integer('QT_MAXIMA_EXECUCAO')->nullable();
            $table->integer('QT_DIAS_PERMANENCIA')->nullable();
            $table->integer('QT_PONTOS')->nullable();
            $table->integer('VL_IDADE_MINIMA')->nullable();
            $table->integer('VL_IDADE_MAXIMA')->nullable();
            $table->integer('VL_SH')->nullable();
            $table->integer('VL_SA')->nullable();
            $table->integer('VL_SP')->nullable();
            $table->string('CO_FINANCIAMENTO')->nullable();
            $table->string('CO_RUBRICA')->nullable();
            $table->integer('QT_TEMPO_PERMANENCIA')->nullable();
            $table->string('DT_COMPETENCIA')->nullable();
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
        Schema::dropIfExists('tb_procedimento');
    }
}
