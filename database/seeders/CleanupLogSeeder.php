<?php

namespace Database\Seeders;

use App\Models\CleanupLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CleanupLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // klo pake yg dari luar di atasnya harus manggil foldernya, trs kalo mau masukin satu data pke create
        CleanupLog::create([
            'id_laporan' => 1,
            'id_petugas' => 2,
            'status_perubahan' => 'sedangdibersihkan',
            'foto_bukti' => 'folder-bukti/contoh-proses.jpg',
            'catatan_petugas' => 'Sedang dalam proses pengangkutan sampah.',
            'waktu_pembaruan' => now(),
        ]);
    }
}
