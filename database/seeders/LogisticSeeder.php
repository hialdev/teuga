<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Logistic;
use App\Models\LogisticAddress;

class LogisticSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     *
     * @return void
     */
    public function run()
    {
        $logistics = [
            [
                'name' => 'PT Ekspedisi Nusantara',
                'npwp' => 1234567890987654,
                'email' => 'info@ekspedisinusantara.com',
                'phone' => '0211234567',
                'address' => 'Jl. Raya Kalimalang No.45, Jakarta Timur',
                'city' => 'Jakarta',
                'postal_code' => '13440',
                'cp_name' => 'Budi Santoso',
                'cp_email' => 'budi@ekspedisinusantara.com',
                'cp_phone' => '081234567890'
            ],
            [
                'name' => 'CV Logistik Mandiri',
                'npwp' => 1234567890987654,
                'email' => 'contact@logistikmandiri.com',
                'phone' => '0319876543',
                'address' => 'Jl. Raya Darmo No.99, Surabaya',
                'city' => 'Surabaya',
                'postal_code' => '60281',
                'cp_name' => 'Siti Rahmawati',
                'cp_email' => 'siti@logistikmandiri.com',
                'cp_phone' => '085678901234'
            ],
            [
                'name' => 'PT Kargo Express',
                'npwp' => 1234567890987654,
                'email' => 'admin@kargoexpress.com',
                'phone' => '022-7654321',
                'address' => 'Jl. Soekarno Hatta No.77, Bandung',
                'city' => 'Bandung',
                'postal_code' => '40234',
                'cp_name' => 'Andi Wijaya',
                'cp_email' => 'andi@kargoexpress.com',
                'cp_phone' => '087654321098'
            ]
        ];

        foreach ($logistics as $logisticData) {
            // Simpan data logistic terlebih dahulu
            $logistic = Logistic::create([
                'name' => $logisticData['name'],
                'npwp' => $logisticData['npwp'],
                'email' => $logisticData['email'],
                'phone' => $logisticData['phone'],
                'address' => $logisticData['address'],
                'city' => $logisticData['city'],
                'postal_code' => $logisticData['postal_code'],
                'cp_name' => $logisticData['cp_name'],
                'cp_email' => $logisticData['cp_email'],
                'cp_phone' => $logisticData['cp_phone']
            ]);

            // Simpan data Logistic Address (Alamat Tambahan)
            LogisticAddress::create([
                'logistic_id' => $logistic->id, // Menggunakan id dari logistic yang baru dibuat
                'name' => 'Gudang ' . explode(' ', $logisticData['name'])[1],
                'address' => 'Jl. Pergudangan Industri No.10, ' . $logisticData['city'],
                'city' => $logisticData['city'],
                'postal_code' => (string) ((int) $logisticData['postal_code'] + 10)
            ]);
        }
    }
}
