<?php

namespace Database\Seeders;

use App\Models\StatusPart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status_parts = [
            [
                'name' => 'GOOD',
                'color' => '#36eb2d'
            ],
            [
                'name' => 'FAULTY',
                'color' => '#eb2d2d'
            ],
        ];

        StatusPart::insert($status_parts);
    }
}
