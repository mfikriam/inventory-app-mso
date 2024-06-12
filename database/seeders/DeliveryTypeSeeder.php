<?php

namespace Database\Seeders;

use App\Models\DeliveryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveryTypes = [
            [
                'name' => 'Delivery Type Demo 1',
                'price' => 120000,
            ],
            [
                'name' => 'Delivery Type Demo 2',
                'price' => 100000,
            ],
            [
                'name' => 'Delivery Type Demo 3',
                'price' => 150000,
            ],
            [
                'name' => 'Delivery Type Demo 4',
                'price' => 80000,
            ],
            [
                'name' => 'Delivery Type Demo 5',
                'price' => 250000,
            ],
        ];

        DeliveryType::insert($deliveryTypes);
    }
}
