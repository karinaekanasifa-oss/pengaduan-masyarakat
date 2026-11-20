<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bikin tabel 'cleanup_logs' buat buku harian / riwayat progres kerja petugas di lapangan
        Schema::create('cleanup_logs', function (Blueprint $table) {

            // ID utama log
            $table->id('id_log');

            // Laporan mana yang lagi di-update progresnya
            $table->unsignedBigInteger('id_laporan');

            // Petugas siapa yang ngelaporin progres di lapangan
            $table->unsignedBigInteger('id_petugas');

            // Perubahan status pengerjaan terkini
            $table->enum('status_perubahan', ['ditugaskan', 'sedangdibersihkan', 'selesai']);

            // Foto bukti lokasi setelah dibersihkan (diunggah oleh petugas)
            $table->string('foto_bukti')->nullable();

            // Catatan tambahan dari petugas mengenai kondisi pembersihan
            $table->text('catatan_petugas')->nullable();

            // Kapan log/progres ini diperbarui
            $table->timestamp('waktu_pembaruan')->useCurrent();

            $table->timestamps();

            // Hubungkan Foreign Key ke tabel laporan dan users
            $table->foreign('id_laporan')->references('id_laporan')->on('waste_reports')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id_pengguna')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleanup_logs');
    }
};
