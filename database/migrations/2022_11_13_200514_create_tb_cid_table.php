<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbCidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cid', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('version_id');
            $table->foreign('version_id')->references('id')->on('versions');
            $table->string('CO_CID')->nullable();
            $table->string('NO_CID')->nullable();
            $table->string('TP_AGRAVO')->nullable();
            $table->string('TP_SEXO')->nullable();
            $table->string('TP_ESTADIO')->nullable();
            $table->integer('VL_CAMPOS_IRRADIADOS')->nullable();
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
        Schema::dropIfExists('tb_cid');
    }
}
