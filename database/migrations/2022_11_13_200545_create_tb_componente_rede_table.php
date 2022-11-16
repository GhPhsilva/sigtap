<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbComponenteRedeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_componente_rede', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');
            $table->string('CO_COMPONENTE_REDE')->nullable();
            $table->string('NO_COMPONENTE_REDE')->nullable();
            $table->string('CO_REDE_ATENCAO')->nullable();
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
        Schema::dropIfExists('tb_componente_rede');
    }
}
