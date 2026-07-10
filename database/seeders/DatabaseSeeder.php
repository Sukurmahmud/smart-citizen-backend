<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ১. ঢাকা বিভাগ তৈরি (ID: 1)
        $division = Division::create([
            'name' => 'Dhaka',
            'bn_name' => 'ঢাকা'
        ]);

        // ২. ঢাকা জেলা তৈরি (ID: 1)
        $district = District::create([
            'division_id' => $division->id,
            'name' => 'Dhaka',
            'bn_name' => 'ঢাকা'
        ]);

        // ৩. ধানমন্ডি উপজেলা/থানা তৈরি (ID: 1)
        $upazila = Upazila::create([
            'district_id' => $district->id,
            'name' => 'Dhanmondi',
            'bn_name' => 'ধানমন্ডি'
        ]);

        // ৪. একটি ইউনিয়ন/ওয়ার্ড তৈরি (ID: 1)
        Union::create([
            'upazila_id' => $upazila->id,
            'name' => 'Ward 15',
            'bn_name' => 'ওয়ার্ড ১৫'
        ]);
    }
}