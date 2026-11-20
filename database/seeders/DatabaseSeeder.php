<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // untuk memanggil semua seedernya
       $this->call([
            ZoneSeeder::class,
            UserAdminSeeder::class,
            UserPetugasSeeder::class,
            UserMasyarakatSeeder::class,
            WasteReportSeeder::class,
            ReportAssignmentSeeder::class,
            CleanupLogSeeder::class,
        ]);
    }
}
