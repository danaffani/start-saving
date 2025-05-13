<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CategoryGroup;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Redirect to Google OAuth page.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle(Request $request)
    {
        // Store the source (login or register) in the session
        if ($request->has('source')) {
            Session::put('oauth_source', $request->source);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google OAuth.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user or create new one
            $user = User::where('email', $googleUser->email)->first();
            $isNewUser = false;

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(24)), // Random secure password
                    'email_verified_at' => now(), // Google accounts are already verified
                ]);
                $isNewUser = true;

                // Create default category groups and categories for the new user
                $this->createDefaultCategoriesForUser($user);
            }

            // Login the user
            Auth::login($user, true);

            // Determine source and set appropriate message
            $source = Session::pull('oauth_source', 'login');
            $message = ($isNewUser)
                ? 'Your account has been created and you are now logged in!'
                : 'You have been successfully logged in!';

            if ($isNewUser && $source === 'register') {
                $message = 'Your account has been created successfully!';
            }

            return redirect()->intended('/home')->with('status', $message);

        } catch (Exception $e) {
            Log::error('Google OAuth callback failed: ' . $e->getMessage());

            return redirect('/login')->withErrors(['error' => 'Google authentication failed. Please try again.']);
        }
    }

    /**
     * Create default category groups and categories for a new user
     *
     * @param User $user
     * @return void
     */
    protected function createDefaultCategoriesForUser(User $user)
    {
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
    }
}
