<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')
                ->constrained('versions')
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->foreignId('table_id')
                ->constrained('tables')
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->string('name');
            $table->integer('import_length');
            $table->integer('import_start');
            $table->integer('import_end');
            $table->string('type');
            $table->timestamps();

            $table->index('table_id','i_columns_table_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('columns');
    }
};
