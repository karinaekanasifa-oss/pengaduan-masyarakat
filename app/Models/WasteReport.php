<?php

namespace App\Models;

// Buat manggil fitur bawaan Laravel (biar bisa pakai factory data dummy & model database)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    // Supaya fitur factory-nya aktif
    use HasFactory;

    // Menegaskan ke Laravel kalau model ini nyambungnya ke tabel database bernama 'waste_reports'
    protected $table = 'waste_reports';

    // Kotak pengaman ($fillable): Daftar kolom yang diizinkan untuk diisi data secara massal (mass assignment)
    protected $fillable = [
        'id_pelapor',        // ID warga yang ngirim laporan
        'id_zona',           // Wilayah/zona lokasi sampah
        'ulasan_warga',      // Komentar atau rating dari warga setelah dibersihkan
        'jenis_sampah',      // Kategori sampah (organik, b3, dll)
        'lokasi',            // Titik koordinat atau alamat tempat sampah
        'foto_kondisi_awal', // Foto bukti tumpukan sampah waktu pertama dilaporin
        'status_laporan',    // Status terkini (misal: 'Dilaporkan', 'Ditugaskan', 'Dibersihkan')
        'dibuat_pada',       // Kapan laporan ini pertama kali dibuat
    ];

    // --- BAGIAN RELASI (PENGHUBUNG ANTAR TABEL) ---

    // Hubungan ke tabel 'users' (sebagai pelapor)
    // Artinya: 1 Laporan Sampah ini MILIK dari 1 orang User (Masyarakat) saja
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_pelapor');
    }

    // Hubungan ke tabel 'zones'
    // Artinya: 1 Laporan Sampah ini BERADA DI 1 Zona tertentu
    public function zona()
    {
        return $this->belongsTo(Zone::class, 'id_zona');
    }

    // Hubungan ke tabel 'report_assignments' (Penugasan)
    // Artinya: 1 Laporan Sampah ini HANYA MEMILIKI 1 tiket penugasan (diberikan ke satu petugas)
    public function assignment()
    {
        return $this->hasOne(ReportAssignment::class, 'id_laporan');
    }

    // Hubungan ke tabel 'cleanup_logs' (Log / Riwayat Pembersihan)
    // Artinya: 1 Laporan Sampah bisa PUNYA BANYAK riwayat/log perubahan status saat petugas kerja di lapangan
    public function cleanupLogs()
    {
        return $this->hasMany(CleanupLog::class, 'id_laporan');
    }
}
