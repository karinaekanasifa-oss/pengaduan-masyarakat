<?php

namespace App\Models;

// Ini buat manggil fitur bawaan Laravel (Factory buat bikin data dummy & Model dasar)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    // Supaya fitur factory-nya bisa dipakai di model ini
    use HasFactory;

    // Menegaskan ke Laravel kalau model "Zone" ini pasangannya sama tabel database bernama 'zones'
    protected$table = 'zones';

    // Menegaskan primary key tabel ini adalah 'id_zona' (karena bukan pakai 'id' standar)
    protected $primaryKey = 'id_zona';

    // Kotak pengaman ($fillable): Kolom apa aja di tabel 'zones' yang boleh diisi datanya
    protected $fillable = [
        'nama_zona',
        'deskripsi_wilayah',
    ];

    // --- BAGIAN RELASI ANTAR TABEL ---

    // Hubungan 1 ke Banyak (One-to-Many) ke tabel 'users'
    // Artinya: 1 Zona bisa diisi atau dimiliki oleh BANYAK Pengguna (warga/petugas)
    public function users()
    {
        return $this->hasMany(User::class, 'id_zona');
    }

    // Hubungan 1 ke Banyak (One-to-Many) ke tabel 'waste_reports' (Laporan Sampah)
    // Artinya: 1 Zona bisa mencakup BANYAK Laporan Sampah dari berbagai titik
    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class, 'id_zona');
    }
}
