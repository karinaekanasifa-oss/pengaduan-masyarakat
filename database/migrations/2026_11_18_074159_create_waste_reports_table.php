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
        // Bikin tabel 'waste_reports' buat nyimpen aduan sampah dari warga
        Schema::create('waste_reports', function (Blueprint $table) {

            // ID utama laporan (Primary Key)
            $table->id('id_laporan');

            // Foreign Key: Nyatet ID warga siapa yang ngirim laporan ini (nyambung ke tabel users)
            $table->unsignedBigInteger('id_pelapor');

            // Foreign Key: Wilayah/zona tempat sampah itu numpuk (nyambung ke tabel zones)
            $table->unsignedBigInteger('id_zona');

            // Ulasan atau rating dari warga setelah lokasi dibersihkan (boleh kosong dulu)
            $table->text('ulasan_warga')->nullable();

            // Pilihan jenis sampah sesuai ketentuan ERD
            $table->enum('jenis_sampah', ['organik', 'anorganik', 'b3', 'campuran']);

            // Alamat detail atau patokan lokasi tumpukan sampah
            $table->string('lokasi');

            // Nama file foto kondisi awal tumpukan sampah waktu dilaporkan
            $table->string('foto_kondisi_awal');

            // Status tahapan laporan dari awal masuk sampai beres
            $table->enum('status_laporan', ['dilaporkan', 'ditugaskan', 'dibersihkan'])->default('dilaporkan');

            // Kapan laporan ini dibuat
            $table->timestamp('dibuat_pada')->useCurrent();

            // Kolom waktu standar Laravel (created_at & updated_at)
            $table->timestamps();

            // Menghubungkan Foreign Key ke tabel induknya masing-masing
            $table->foreign('id_pelapor')->references('id_pengguna')->on('users')->onDelete('cascade');
            $table->foreign('id_zona')->references('id_zona')->on('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perintah buat ngapus tabel kalau migration di-rollback
        Schema::dropIfExists('waste_reports');
    }
};
