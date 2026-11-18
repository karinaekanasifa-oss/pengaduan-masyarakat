<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bikin tabel baru dengan nama 'zones' di database
        Schema::create('zones', function (Blueprint $table) {

            // Kolom ID utama (Primary Key) yang otomatis nambah angkanya (1, 2, 3, dst)
            $table->id('id_zona');

            // Kolom buat nyimpen nama wilayah/kecamatan (tipe teks pendek max 255 karakter)
            $table->string('nama_zona');

            // Kolom buat penjelasan lengkap wilayahnya (tipe teks panjang)
            $table->text('deskripsi_wilayah')->nullable();

            // Otomatis bikin kolom 'created_at' & 'updated_at' buat nyatet waktu buat & update data
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Perintah buat ngapus tabel 'zones' kalau sewaktu-waktu migration-nya di-rollback
        Schema::dropIfExists('zones');
    }
};
