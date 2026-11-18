<?php

namespace App\Models;

// Buat manggil fitur bawaan Laravel (buat factory data dummy & model database)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleanupLog extends Model
{
    // Supaya fitur factory-nya aktif
    use HasFactory;

    // Menegaskan ke Laravel kalau model ini nyambungnya ke tabel database bernama 'cleanup_logs'
    protected $table = 'cleanup_logs';

    // Kotak pengaman ($fillable): Daftar kolom yang diizinkan untuk diisi data secara massal
    protected $fillable = [
        'id_laporan',       // ID laporan sampah yang sedang dikerjakan
        'id_petugas',       // ID petugas kebersihan yang melakukan pembaruan di lapangan
        'status_perubahan', // Status progres terkini (misal: 'Ditugaskan', 'Sedang Dibersihkan', 'Selesai')
        'foto_bukti',       // Foto hasil jepretan petugas setelah sampah dibersihkan
        'catatan_petugas',  // Keterangan tambahan dari petugas mengenai kondisi di lapangan
        'waktu_pembaruan',  // Kapan status atau foto ini di-update oleh petugas
    ];

    // --- BAGIAN RELASI (PENGHUBUNG ANTAR TABEL) ---

    // Hubungan ke tabel 'waste_reports' (Laporan Sampah)
    // Artinya: Catatan log/riwayat ini MILIK dari 1 Laporan Sampah tertentu
    public function laporan()
    {
        return $this->belongsTo(WasteReport::class, 'id_laporan');
    }

    // Hubungan ke tabel 'users' (sebagai Petugas)
    // Artinya: Catatan log/riwayat ini DIBUAT/DI-UPDATE OLEH 1 orang User (Petugas Kebersihan)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }
}
