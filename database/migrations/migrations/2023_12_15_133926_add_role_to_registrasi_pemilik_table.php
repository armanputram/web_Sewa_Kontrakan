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
        Schema::table('registrasi_penyewa', function (Blueprint $table) {
            $table->string('role')->default('penyewa'); // Tambahkan kolom role
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrasi_penyewa', function (Blueprint $table) {
            $table->dropColumn('role'); // Jika perlu rollback, hapus kolom role
        });
    }
};
