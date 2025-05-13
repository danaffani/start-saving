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
            ['name' => 'User 1', 'email' => 'user1@test.com', 'password' => bcrypt('password')],
            ['name' => 'User 2', 'email' => 'user2@test.com', 'password' => bcrypt('password')],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
