<?php

namespace Database\Seeders;

use App\Models\WasteReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WasteReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // klo pake yg dari luar di atasnya harus manggil foldernya, trs kalo mau masukin satu data pke create
        WasteReport::create([
            'id_pelapor' => 3,
            'id_zona' => 1,
            'ulasan_warga' => 'Sampah menumpuk di dekat saluran air.',
            'jenis_sampah' => 'campuran',
            'lokasi' => 'Jl. Melati No. 12',
            'foto_kondisi_awal' => 'folder-laporan/contoh-sampah.jpg',
            'status_laporan' => 'dilaporkan',
        ]);
    }
}
