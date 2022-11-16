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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')
                ->constrained('versions')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->string('name');
            $table->json('column_names');
            $table->string('data_file');
            $table->boolean('imported')->default(false);
            $table->timestamps();

            $table->index('version_id','i_tables_version_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
};
