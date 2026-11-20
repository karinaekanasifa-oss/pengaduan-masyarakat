<?php

namespace App\Models;

// Buat manggil fitur bawaan Laravel (buat factory data dummy & model database)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAssignment extends Model
{
    // Supaya fitur factory-nya aktif
    use HasFactory;

    // Menegaskan ke Laravel kalau model ini nyambungnya ke tabel database bernama 'report_assignments'
    protected $table = 'report_assignments';

    // Menegaskan primary key tabel ini adalah 'id_penugasan'
    protected $primaryKey = 'id_penugasan';

    // Kotak pengaman ($fillable): Daftar kolom yang diizinkan untuk diisi data secara massal
    protected $fillable = [
        'id_laporan',       // ID laporan sampah yang mau dikerjakan
        'id_petugas',       // ID petugas kebersihan yang ditugasin di lapangan
        'id_admin',         // ID admin yang bikin/memberikan penugasan ini
        'waktu_ditugaskan', // Kapan tugas ini resmi diberikan oleh admin
        'catatan_admin',    // Pesan atau instruksi tambahan dari admin buat si petugas
        'estimasi_selesai', // Perkiraan kapan tugas ini harusnya beres
    ];

    // --- BAGIAN RELASI (PENGHUBUNG ANTAR TABEL) ---

    // Hubungan ke tabel 'waste_reports' (Laporan Sampah)
    // Artinya: Tiket penugasan ini HANYA UNTUK 1 Laporan Sampah tertentu
    public function laporan()
    {
        return $this->belongsTo(WasteReport::class, 'id_laporan');
    }

    // Hubungan ke tabel 'users' (sebagai Petugas)
    // Artinya: Tiket penugasan ini DIKERJAKAN OLEH 1 orang User (Petugas Kebersihan)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }

    // Hubungan ke tabel 'users' (sebagai Admin)
    // Artinya: Tiket penugasan ini DIBUAT OLEH 1 orang User (Admin Dinas)
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin');
    }
}
