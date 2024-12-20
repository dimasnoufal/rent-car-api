<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id');
            $table->bigInteger('car_id');
            $table->string('alamat');
            $table->string('tanggal_pemesanan');
            $table->string('tanggal_pengembalian');
            $table->string('nama_pemesan');
            $table->string('no_hp');
            $table->string('foto_ktp');
            $table->string('foto_sim');
            $table->string('status')->default('BOOKING');
            $table->string('layanan_supir')->default('NO');
            $table->bigInteger('total_harga')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
