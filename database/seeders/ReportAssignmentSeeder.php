<?php

namespace Database\Seeders;

use App\Models\ReportAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // klo pake yg dari luar di atasnya harus manggil foldernya, trs kalo mau masukin satu data pke create
        ReportAssignment::create([
            'id_laporan' => 1,
            'id_petugas' => 2,
            'id_admin' => 1,
            'waktu_ditugaskan' => now(),
            'catatan_admin' => 'Segera dibersihkan hari ini.',
            'estimasi_selesai' => now()->addHours(3),
        ]);
    }
}
