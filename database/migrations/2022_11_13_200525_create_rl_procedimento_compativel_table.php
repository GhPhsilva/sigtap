<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRlProcedimentoCompativelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rl_procedimento_compativel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');
            $table->string('CO_PROCEDIMENTO_PRINCIPAL')->nullable();
            $table->string('CO_REGISTRO_PRINCIPAL')->nullable();
            $table->string('CO_PROCEDIMENTO_COMPATIVEL')->nullable();
            $table->string('CO_REGISTRO_COMPATIVEL')->nullable();
            $table->string('TP_COMPATIBILIDADE')->nullable();
            $table->integer('QT_PERMITIDA')->nullable();
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
        Schema::dropIfExists('rl_procedimento_compativel');
    }
}
