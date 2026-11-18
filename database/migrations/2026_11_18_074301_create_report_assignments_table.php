<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bikin tabel 'report_assignments' buat surat tugas dari admin ke petugas
        Schema::create('report_assignments', function (Blueprint $table) {

            // ID utama penugasan
            $table->id('id_penugasan');

            // Laporan sampah mana yang mau dikerjakan
            $table->unsignedBigInteger('id_laporan');

            // Petugas kebersihan siapa yang dikasih tugas ke lapangan
            $table->unsignedBigInteger('id_petugas');

            // Admin siapa yang mendelegasikan tugas ini
            $table->unsignedBigInteger('id_admin');

            // Waktu tugas ini resmi diberikan
            $table->timestamp('waktu_ditugaskan')->useCurrent();

            // Catatan atau instruksi khusus dari admin buat petugas
            $table->text('catatan_admin')->nullable();

            // Perkiraan kapan tugas ini harus selesai
            $table->timestamp('estimasi_selesai')->nullable();

            $table->timestamps();

            // Hubungkan Foreign Key ke tabel laporan dan tabel users
            $table->foreign('id_laporan')->references('id_laporan')->on('waste_reports')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id_pengguna')->on('users')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_pengguna')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_assignments');
    }
};
