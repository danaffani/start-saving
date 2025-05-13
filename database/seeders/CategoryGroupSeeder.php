<?php

namespace Database\Seeders;

use App\Models\CategoryGroup;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryGroups = [
            ['id' => 1, 'name' => 'Bills', 'slug' => 'bills'],
            ['id' => 2, 'name' => 'Needs', 'slug' => 'needs'],
            ['id' => 3, 'name' => 'Wants', 'slug' => 'wants'],
        ];

        foreach (User::all() as $user) {
            foreach ($categoryGroups as $categoryGroup) {
                CategoryGroup::create([
                    'user_id' => $user->id,
                    'name' => $categoryGroup['name'],
                    'slug' => $categoryGroup['slug'],
                ]);
            }
        }
    }
}
