<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path file SQL
        $filePath = database_path('sql/accounting_dummy.sql');

        // Membaca isi file SQL
        $sql = file_get_contents($filePath);

        // Eksekusi SQL
        DB::connection('accounting')->unprepared($sql);

        // Tampilkan pesan sukses
        $this->command->info('Inital for Accounting Table (Account, Master Account, Bank) seeded successfully!');
    }
}
