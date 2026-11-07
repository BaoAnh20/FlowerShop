<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // User::factory(10)->create();

       $users = [
            [
                'name' => 'Admin BAnh',
                'email' => 'admin@gmail.vn',
                'password' => 'Admin@123',
                'user_type' => 'admin',
            ],
            [
                'name' => 'Tống Bảo Anh',
                'email' => 'baoanh@gmail.com',
                'password' => 'User@123',
                'user_type' => 'user',
            ],
            [
                'name' => 'Tuấn Khang',
                'email' => 'tuankhang@gmail.com',
                'password' => 'User@123',
                'user_type' => 'user',
            ],
   
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'user_type' => $data['user_type'],
                ]
            );
            
        }
    }
}
