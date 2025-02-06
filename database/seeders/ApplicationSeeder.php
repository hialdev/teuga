<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applications = [
            [
                'code' => 'SSO',
                'name' => 'SSO',
                'url' => 'http://sso.teuga.test',
            ],
            [
                'code' => 'ACC',
                'name' => 'Account',
                'url' => 'http://acc.teuga.test',
            ],
            [
                'code' => 'OSN',
                'name' => 'OSANO',
                'url' => 'http://osn.teuga.test',
            ],
        ];

        foreach ($applications as $app) {
            Application::firstOrCreate(
                ['code' => $app['code']], // Cek jika data sudah ada berdasarkan kode
                $app // Data yang akan disimpan jika belum ada
            );
        }

        $this->command->info('Applications created successfully.');
    }
}
