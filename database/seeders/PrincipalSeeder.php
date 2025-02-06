<?php

namespace Database\Seeders;

use App\Models\Principal;
use App\Models\PrincipalPic;
use App\Models\PrincipalAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrincipalSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $principals = [
                [
                    'name' => 'PT. Beton Jaya Abadi',
                    'npwp' => 1234567890987654,
                    'email' => 'bja@mail.com',
                    'phone' => '021923212',
                    'address' => 'Jl. Gatot Subroto No. 12',
                    'city' => 'Jakarta Selatan',
                    'postal_code' => '12950'
                ],
                [
                    'name' => 'PT. Konstruksi Nusantara',
                    'npwp' => 1234567890987654,
                    'email' => 'kontara@mail.com',
                    'phone' => '08712322322',
                    'address' => 'Jl. Sudirman No. 45',
                    'city' => 'Surabaya',
                    'postal_code' => '60271'
                ]
            ];

            foreach ($principals as $principalData) {
                $principal = Principal::create($principalData);

                // Tambahkan PIC untuk setiap Principal
                PrincipalPic::create([
                    'principal_id' => $principal->id,
                    'name' => 'Pak '.strtolower(str_replace(' ', '', $principal->name)),
                    'email' => 'budi.' . strtolower(str_replace(' ', '', $principal->name)) . '@example.com'
                ]);

                // Tambahkan alamat tambahan untuk proyek
                PrincipalAddress::create([
                    'principal_id' => $principal->id,
                    'name' => 'Representative Office - ' . $principal->city,
                    'address' => 'Jl. Industri No. 10',
                    'city' => $principal->city,
                    'postal_code' => $principal->postal_code
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}