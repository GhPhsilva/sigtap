<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRlProcedimentoIncrementoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rl_procedimento_incremento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');
            $table->string('CO_PROCEDIMENTO')->nullable();
            $table->string('CO_HABILITACAO')->nullable();
            $table->integer('VL_PERCENTUAL_SH')->nullable();
            $table->integer('VL_PERCENTUAL_SA')->nullable();
            $table->integer('VL_PERCENTUAL_SP')->nullable();
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
        Schema::dropIfExists('rl_procedimento_incremento');
    }
}
