<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Menegaskan nama tabel di database
    protected $table = 'users';

    // Menegaskan primary key tabel ini adalah 'id_pengguna'
    protected $primaryKey = 'id_pengguna';

    // Kolom yang boleh diisi datanya (sesuai ERD dan kebutuhan input)
    protected $fillable = [
        'id_zona',
        'nama_lengkap',
        'email',
        'password',
        'no_telepon',
        'peran', // Pilihan: 'Masyarakat', 'Petugas', 'Admin'
    ];

    // Menyembunyikan password agar aman tidak terekspos
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Otomatis mengenkripsi password
        ];
    }

    // ==========================================
    // BAGIAN RELASI ANTAR TABEL (SESUAI ERD)
    // ==========================================

    // User terdaftar di 1 Zona tertentu
    public function zona()
    {
        return $this->belongsTo(Zone::class, 'id_zona');
    }

    // Khusus Masyarakat: 1 orang bisa mengirim banyak Laporan Sampah
    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class, 'id_pelapor');
    }

    // Khusus Petugas: 1 orang bisa menerima banyak tugas dari admin
    public function assignmentsAsPetugas()
    {
        return $this->hasMany(ReportAssignment::class, 'id_petugas');
    }

    // Khusus Admin: 1 orang bisa membuat banyak penugasan
    public function assignmentsAsAdmin()
    {
        return $this->hasMany(ReportAssignment::class, 'id_admin');
    }

    // Khusus Petugas: 1 orang bisa mencatat banyak log/riwayat pembersihan di lapangan
    public function cleanupLogs()
    {
        return $this->hasMany(CleanupLog::class, 'id_petugas');
    }
}
