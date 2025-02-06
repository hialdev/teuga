<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\ClientPic;
use App\Models\ClientAddress;

class ClientSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'name' => 'PT Bangun Jaya Abadi',
                'npwp' => 1234567890987654,
                'address' => 'Jl. Gatot Subroto No. 15',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12750'
            ],
            [
                'name' => 'CV Mandiri Perkasa',
                'npwp' => 1234567890987654,
                'address' => 'Jl. Ahmad Yani No. 45',
                'city' => 'Surabaya',
                'postal_code' => '60234'
            ],
            [
                'name' => 'PT Cahaya Sejahtera',
                'npwp' => 1234567890987654,
                'address' => 'Jl. Merdeka No. 10',
                'city' => 'Bandung',
                'postal_code' => '40123'
            ]
        ];

        foreach ($clients as $clientData) {
            // Simpan data Client terlebih dahulu
            $client = Client::create([
                'id' => Str::uuid(),
                'name' => $clientData['name'],
                'npwp' => $clientData['npwp'],
                'address' => $clientData['address'],
                'city' => $clientData['city'],
                'postal_code' => $clientData['postal_code'],
            ]);

            // Simpan data Client PIC
            ClientPic::create([
                'id' => Str::uuid(),
                'client_id' => $client->id, // Pastikan Client ID benar
                'name' => 'Bapak ' . explode(' ', $clientData['name'])[1],
                'email' => strtolower(str_replace(' ', '', $clientData['name'])) . '@example.com',
            ]);

            // Simpan data Client Address (Alamat Proyek)
            ClientAddress::create([
                'id' => Str::uuid(),
                'client_id' => $client->id, // Pastikan Client ID benar
                'name' => 'Proyek ' . explode(' ', $clientData['name'])[1],
                'address' => 'Jl. Proyek ' . explode(' ', $clientData['name'])[1] . ' No. 20',
                'city' => $clientData['city'],
                'postal_code' => (string) ((int) $clientData['postal_code'] + 10)
            ]);
        }
    }
}
