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
        Schema::create('kabupatenkotas', function (Blueprint $table) {
            $table->id('id_kabupaten');
            $table->unsignedBigInteger('id_provinsi');
            $table->string('nama_kabupaten');
            $table->timestamps();

            $table->foreign('id_provinsi')->references('id_provinsi')->on('provinsis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kabupatenkotas');
    }
};
