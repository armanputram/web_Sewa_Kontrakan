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
        Schema::create('properti', function (Blueprint $table) {
            $table->id('id_properti');
            $table->string('nama');
            $table->string('alamat');
            $table->integer('harga');
            $table->string('no_handphone', 12);
            $table->string('deskripsi');
            $table->json('foto')->nullable();
            $table->unsignedBigInteger('id_pemilik');
            $table->timestamps();

            $table->foreign('id_pemilik')->references('id_pemilik')->on('registrasi_pemilik');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properti');
    }
};
