<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ১. প্রথমে বাংলাদেশ এরিয়া সিডারটি রান করা হবে
        $this->call(BangladeshAreaSeeder::class);

        // ২. তারপর কর্মকর্তা/ইউজার তৈরি করা হবে
        User::create([
            'phone' => '01794678759',
            'password' => Hash::make('12345678'),
            'role' => 'representative', 
            'status' => 'active',
        ]);
    }
}