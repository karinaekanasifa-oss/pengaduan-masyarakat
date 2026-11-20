<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // klo pake yg dari luar di atasnya harus manggil foldernya, trs kalo mau masukin satu data pke create
        Zone::create([
            'nama_zona' => 'Zona A - Melati',
            'deskripsi_wilayah' => 'Kawasan perumahan blok A dan sekitar pasar.',
        ]);
    }
}
