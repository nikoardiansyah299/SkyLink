<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate([
            'email' => 'test@travelid.com'
        ], [
            'name' => 'Test User',
            'username' => 'testuser',
            'roles' => 'client',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Additional test user (admin)
        User::firstOrCreate([
            'email' => 'admin@travelid.com'
        ], [
            'name' => 'Admin User',
            'username' => 'admin',
            'roles' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        ]);

        $this->call([
            MaskapaiSeeder::class,
            BandaraSeeder::class,
            FeaturedFlightsSeeder::class,
        ]);
    }
}
