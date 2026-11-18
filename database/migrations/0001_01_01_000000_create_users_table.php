<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bikin tabel 'users' untuk nampung akun Masyarakat, Petugas, dan Admin sekaligus
        Schema::create('users', function (Blueprint $table) {

            // Kolom ID utama untuk user
            $table->id('id_pengguna');

            // Kolom Foreign Key ke tabel 'zones' (boleh kosong / nullable kalau daftar mandiri)
            $table->unsignedBigInteger('id_zona')->nullable();

            // Nama lengkap pengguna
            $table->string('nama_lengkap');

            // Email untuk login (sifatnya wajib unik / gak boleh kembar)
            $table->string('email')->unique();

            // Buat nyimpen status verifikasi email (bawaan Laravel, biarin aja)
            $table->timestamp('email_verified_at')->nullable();

            // Buat nyimpen password akun yang sudah dienkripsi (hashed)
            $table->string('password');

            // Nomor telepon user (dibatasi 20 karakter)
            $table->string('no_telepon', 20);

            // Kolom penanda role/hak akses (pilihannya cuma 3 sesuai aturan ERD)
            $table->enum('peran', ['Masyarakat', 'Petugas', 'Admin']);

            // Fitur bawaan Laravel buat "Remember Me" pas login
            $table->rememberToken();

            // Kolom waktu buat & update data otomatis
            $table->timestamps();

            // Aturan relasi: Sambungin kolom 'id_zona' ke kolom 'id_zona' di tabel 'zones'
            $table->foreign('id_zona')->references('id_zona')->on('zones')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
