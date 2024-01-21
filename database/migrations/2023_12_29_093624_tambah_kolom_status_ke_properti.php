<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahKolomStatusKeProperti extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'setuju', 'tolak'])->default('menunggu');
        });
    }

    /**
     * Mundurkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
