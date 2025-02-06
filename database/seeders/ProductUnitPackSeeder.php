<?php

namespace Database\Seeders;

use App\Models\Pack;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductUnitPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            // 1. Simpan Units terlebih dahulu dan simpan ID-nya
            $unitIds = [];
            $unitData = [
                ['name' => 'Liter', 'code' => 'L'],
                ['name' => 'Kilogram', 'code' => 'Kg'],
                ['name' => 'Pack', 'code' => 'pcs'],
                ['name' => 'Sak', 'code' => 'zak'],
            ];

            foreach ($unitData as $unit) {
                $createdUnit = Unit::create($unit);
                $unitIds[$unit['name']] = $createdUnit->id; // Simpan ID unit yang baru dibuat
            }

            // 2. Simpan Products dengan unit_id yang benar
            $productsData = [
                ['name' => 'Produk A', 'unit_id' => $unitIds['Liter']],
                ['name' => 'Produk B', 'unit_id' => $unitIds['Kilogram']],
                ['name' => 'Produk C', 'unit_id' => $unitIds['Pack']],
                ['name' => 'Produk D', 'unit_id' => $unitIds['Sak']],
            ];

            foreach ($productsData as $product) {
                Product::create($product);
            }

            // 3. Simpan Packs dengan unit_id yang valid
            $packsData = [
                ['name' => 'Drum', 'capacity' => 500, 'unit_id' => $unitIds['Liter']],
                ['name' => 'Peti Kayu', 'capacity' => 10, 'unit_id' => $unitIds['Kilogram']],
                ['name' => 'Box', 'capacity' => 650, 'unit_id' => $unitIds['Pack']],
                ['name' => 'Regular Box', 'capacity' => 320, 'unit_id' => $unitIds['Sak']],
            ];

            foreach ($packsData as $pack) {
                Pack::create($pack);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
