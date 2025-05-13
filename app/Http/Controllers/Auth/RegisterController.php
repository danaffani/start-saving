<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CategoryGroup;
use App\Models\Category;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Create default category groups for the new user
        $categoryGroups = [
            ['name' => 'Bills', 'slug' => 'bills'],
            ['name' => 'Needs', 'slug' => 'needs'],
            ['name' => 'Wants', 'slug' => 'wants'],
        ];

        $groupIds = [];
        foreach ($categoryGroups as $group) {
            $categoryGroup = CategoryGroup::create([
                'user_id' => $user->id,
                'name' => $group['name'],
                'slug' => $group['slug'],
            ]);
            $groupIds[$group['name']] = $categoryGroup->id;
        }

        // Create default categories for the new user
        $categories = [
            // Bills
            [
                'category_group_id' => $groupIds['Bills'],
                'name' => 'Utilities',
                'slug' => 'utilities',
                'description' => 'Electricity, water, gas bills'
            ],
            [
                'category_group_id' => $groupIds['Bills'],
                'name' => 'TV, phone and internet',
                'slug' => 'tv-phone-internet',
                'description' => 'TV subscription, phone bills, internet bills'
            ],
            [
                'category_group_id' => $groupIds['Bills'],
                'name' => 'Insurance',
                'slug' => 'insurance',
                'description' => 'Health insurance, car insurance, home insurance'
            ],
            [
                'category_group_id' => $groupIds['Bills'],
                'name' => 'Music',
                'slug' => 'music',
                'description' => 'Music subscriptions'
            ],
            [
                'category_group_id' => $groupIds['Bills'],
                'name' => 'TV streaming',
                'slug' => 'tv-streaming',
                'description' => 'Netflix, Disney+, HBO, etc.'
            ],

            // Needs
            [
                'category_group_id' => $groupIds['Needs'],
                'name' => 'Transportation',
                'slug' => 'transportation',
                'description' => 'Public transportation, ride sharing'
            ],
            [
                'category_group_id' => $groupIds['Needs'],
                'name' => 'Car maintenance',
                'slug' => 'car-maintenance',
                'description' => 'Car repairs, oil changes, car wash'
            ],
            [
                'category_group_id' => $groupIds['Needs'],
                'name' => 'Home maintenance',
                'slug' => 'home-maintenance',
                'description' => 'Home repairs, cleaning services'
            ],

            // Wants
            [
                'category_group_id' => $groupIds['Wants'],
                'name' => 'Holidays & gifts',
                'slug' => 'holidays-gifts',
                'description' => 'Holiday expenses, gifts for others'
            ],
            [
                'category_group_id' => $groupIds['Wants'],
                'name' => 'Shopping',
                'slug' => 'shopping',
                'description' => 'Clothing, electronics, etc.'
            ],
            [
                'category_group_id' => $groupIds['Wants'],
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Vacations, trips, flights, hotels'
            ],
            [
                'category_group_id' => $groupIds['Wants'],
                'name' => 'YNAB subscription',
                'slug' => 'ynab-subscription',
                'description' => 'You Need A Budget subscription'
            ],
            [
                'category_group_id' => $groupIds['Wants'],
                'name' => 'Stuff I forgot to budget for',
                'slug' => 'stuff-i-forgot',
                'description' => 'Miscellaneous expenses not budgeted elsewhere'
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'user_id' => $user->id,
                'category_group_id' => $category['category_group_id'],
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'] ?? null,
            ]);
        }

        return $user;
    }
}
