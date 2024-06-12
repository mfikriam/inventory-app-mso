<?php

namespace Database\Seeders;

use App\Models\Witel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WitelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $witels = [
            [
                'delivery_type_id' => 1,
                'name' => 'Sulawesi Utara',
            ],
            [
                'delivery_type_id' => 2,
                'name' => 'Gorontalo',
            ],
            [
                'delivery_type_id' => 3,
                'name' => 'Sulawesi Selatan',
            ],
            [
                'delivery_type_id' => 4,
                'name' => 'Sulawesi Tengah',
            ],
            [
                'delivery_type_id' => 5,
                'name' => 'Sulawesi Tenggara',
            ],
            [
                'delivery_type_id' => 1,
                'name' => 'Sulawesi Barat',
            ],
        ];

        Witel::insert($witels);
    }
}
