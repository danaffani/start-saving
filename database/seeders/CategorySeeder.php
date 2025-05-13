<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Bills
            [
                'category_group_id' => 1,
                'name' => 'Utilities',
                'slug' => 'utilities',
                'description' => 'Electricity, water, gas bills'
            ],
            [
                'category_group_id' => 1,
                'name' => 'TV, phone and internet',
                'slug' => 'tv-phone-internet',
                'description' => 'TV subscription, phone bills, internet bills'
            ],
            [
                'category_group_id' => 1,
                'name' => 'Insurance',
                'slug' => 'insurance',
                'description' => 'Health insurance, car insurance, home insurance'
            ],
            [
                'category_group_id' => 1,
                'name' => 'Music',
                'slug' => 'music',
                'description' => 'Music subscriptions'
            ],
            [
                'category_group_id' => 1,
                'name' => 'TV streaming',
                'slug' => 'tv-streaming',
                'description' => 'Netflix, Disney+, HBO, etc.'
            ],

            // Needs
            [
                'category_group_id' => 2,
                'name' => 'Transportation',
                'slug' => 'transportation',
                'description' => 'Public transportation, ride sharing'
            ],
            [
                'category_group_id' => 2,
                'name' => 'Car maintenance',
                'slug' => 'car-maintenance',
                'description' => 'Car repairs, oil changes, car wash'
            ],
            [
                'category_group_id' => 2,
                'name' => 'Home maintenance',
                'slug' => 'home-maintenance',
                'description' => 'Home repairs, cleaning services'
            ],

            // Wants
            [
                'category_group_id' => 3,
                'name' => 'Holidays & gifts',
                'slug' => 'holidays-gifts',
                'description' => 'Holiday expenses, gifts for others'
            ],
            [
                'category_group_id' => 3,
                'name' => 'Shopping',
                'slug' => 'shopping',
                'description' => 'Clothing, electronics, etc.'
            ],
            [
                'category_group_id' => 3,
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Vacations, trips, flights, hotels'
            ],
            [
                'category_group_id' => 3,
                'name' => 'YNAB subscription',
                'slug' => 'ynab-subscription',
                'description' => 'You Need A Budget subscription'
            ],
            [
                'category_group_id' => 3,
                'name' => 'Stuff I forgot to budget for',
                'slug' => 'stuff-i-forgot',
                'description' => 'Miscellaneous expenses not budgeted elsewhere'
            ]
        ];

        foreach (User::all() as $user) {
            foreach ($categories as $category) {
                Category::create([
                    'user_id' => $user->id,
                    'category_group_id' => $category['category_group_id'],
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                ]);
            }
        }
    }
}
