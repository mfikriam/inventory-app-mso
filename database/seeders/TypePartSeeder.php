<?php

namespace Database\Seeders;

use App\Models\TypePart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type_parts = [
            [
                'name' => 'NEC',
            ],
            [
                'name' => 'ERICSON',
            ],
        ];

        TypePart::insert($type_parts);
    }
}
