<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // klo pake yg dari luar di atasnya harus manggil foldernya, trs kalo mau masukin satu data pke create
        User::create([
            'id_zona' => null,
            'nama_lengkap' => 'Admin Dinas Kebersihan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'no_telepon' => '081234567890',
            'peran' => 'Admin',
        ]);
    }
}
