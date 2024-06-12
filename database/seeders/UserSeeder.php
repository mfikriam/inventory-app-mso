<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'is_admin' => 1,
                'password' => bcrypt('123123123')
            ],
            [
                'name' => 'Teknisi',
                'username' => 'teknisi',
                'is_admin' => 0,
                'password' => bcrypt('123123123')
            ]
        ];

        User::insert($users);
    }
}
