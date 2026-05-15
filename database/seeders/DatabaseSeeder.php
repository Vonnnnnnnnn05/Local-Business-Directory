<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Restaurants', 'description' => 'Food, cafes, and dining establishments.'],
            ['name' => 'Salons', 'description' => 'Hair, beauty, and grooming services.'],
            ['name' => 'Stores', 'description' => 'Retail shops and community stores.'],
            ['name' => 'Hotels', 'description' => 'Accommodation and lodging.'],
            ['name' => 'Clinics', 'description' => 'Health and wellness providers.'],
            ['name' => 'Services', 'description' => 'Repair, professional, and household services.'],
        ])->mapWithKeys(fn ($category) => [
            $category['name'] => Category::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category
            ),
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'System Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            ['name' => 'Business Owner', 'password' => Hash::make('password'), 'role' => 'owner', 'phone' => '0917-555-0101']
        );

        $business = Business::firstOrCreate(
            ['slug' => 'sunrise-cafe-demo'],
            [
                'owner_id' => $owner->id,
                'category_id' => $categories['Restaurants']->id,
                'name' => 'Sunrise Cafe',
                'contact_number' => '0917-123-4567',
                'email' => 'hello@sunrisecafe.test',
                'address' => '123 Market Street',
                'city' => 'Local City',
                'description' => 'A friendly neighborhood cafe serving breakfast plates, coffee, pastries, and quick lunches for the community.',
                'status' => 'approved',
            ]
        );

        foreach (['Breakfast plates', 'Fresh coffee', 'Pastries'] as $service) {
            $business->services()->firstOrCreate(['name' => $service], ['description' => 'Available daily.']);
        }

        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
            $business->hours()->firstOrCreate(['day' => $day], ['opens_at' => '08:00', 'closes_at' => '18:00']);
        }
    }
}
