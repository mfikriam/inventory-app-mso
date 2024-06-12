<?php

namespace Database\Seeders;

use App\Models\StatusExdismentie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusExdismentieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status_exdismenties = [
            [
                'name' => 'POSTMAGER',
                'color' => '#36eb2d'
            ],
            [
                'name' => 'PERPINDAHAN GUDANG',
                'color' => '#dbe349'
            ],
            [
                'name' => 'NONE',
                'color' => '#eb2d2d'
            ],
        ];

        StatusExdismentie::insert($status_exdismenties);
    }
}
