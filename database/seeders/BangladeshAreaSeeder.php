<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BangladeshAreaSeeder extends Seeder
{
    public function run(): void
    {
        // ১. আগে পুরানো ডাটা পরিষ্কার করা (যাতে ডুপ্লিকেট না হয়)
        DB::statement('PRAGMA foreign_keys = OFF;'); // SQLite এর জন্য ফরেন কি অফ করা
        DB::table('unions')->truncate();
        DB::table('upazilas')->truncate();
        DB::table('districts')->truncate();
        DB::table('divisions')->truncate();
        DB::statement('PRAGMA foreign_keys = ON;');

        // ২. বিভাগসমূহ (Divisions) ইনসার্ট (কলাম: bn_name)
        $divisions = [
            ['id' => 1, 'name' => 'Dhaka', 'bn_name' => 'ঢাকা'],
            ['id' => 2, 'name' => 'Chattogram', 'bn_name' => 'চট্টগ্রাম'],
            ['id' => 3, 'name' => 'Rajshahi', 'bn_name' => 'রাজশাহী'],
            ['id' => 4, 'name' => 'Khulna', 'bn_name' => 'খুলনা'],
            ['id' => 5, 'name' => 'Barishal', 'bn_name' => 'বরিশাল'],
            ['id' => 6, 'name' => 'Sylhet', 'bn_name' => 'সিলেট'],
            ['id' => 7, 'name' => 'Rangpur', 'bn_name' => 'রংপুর'],
            ['id' => 8, 'name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ'],
        ];
        DB::table('divisions')->insert($divisions);

        // ৩. জেলাসমূহ (Districts) ইনসার্ট (কলাম: bn_name)
        $districts = [
            // ঢাকা বিভাগ (ID: 1)
            ['id' => 1, 'division_id' => 1, 'name' => 'Dhaka', 'bn_name' => 'ঢাকা'],
            ['id' => 2, 'division_id' => 1, 'name' => 'Gazipur', 'bn_name' => 'গাজীপুর'],
            ['id' => 3, 'division_id' => 1, 'name' => 'Narayanganj', 'bn_name' => 'নারায়ণগঞ্জ'],
            ['id' => 4, 'division_id' => 1, 'name' => 'Tangail', 'bn_name' => 'টাঙ্গাইল'],
            ['id' => 5, 'division_id' => 1, 'name' => 'Faridpur', 'bn_name' => 'ফরিদপুর'],

            // চট্টগ্রাম বিভাগ (ID: 2)
            ['id' => 6, 'division_id' => 2, 'name' => 'Chattogram', 'bn_name' => 'চট্টগ্রাম'],
            ['id' => 7, 'division_id' => 2, 'name' => 'Cox\'s Bazar', 'bn_name' => 'কক্সবাজার'],
            ['id' => 8, 'division_id' => 2, 'name' => 'Cumilla', 'bn_name' => 'কুমিল্লা'],

            // রাজশাহী বিভাগ (ID: 3)
            ['id' => 9, 'division_id' => 3, 'name' => 'Rajshahi', 'bn_name' => 'রাজশাহী'],
            ['id' => 10, 'division_id' => 3, 'name' => 'Bogra', 'bn_name' => 'বগুড়া'],

            // খুলনা বিভাগ (ID: 4)
            ['id' => 11, 'division_id' => 4, 'name' => 'Khulna', 'bn_name' => 'খুলনা'],
            ['id' => 12, 'division_id' => 4, 'name' => 'Jessore', 'bn_name' => 'যশোর'],

            // বরিশাল বিভাগ (ID: 5)
            ['id' => 13, 'division_id' => 5, 'name' => 'Barishal', 'bn_name' => 'বরিশাল'],

            // সিলেট বিভাগ (ID: 6)
            ['id' => 14, 'division_id' => 6, 'name' => 'Sylhet', 'bn_name' => 'সিলেট'],

            // রংপুর বিভাগ (ID: 7)
            ['id' => 15, 'division_id' => 7, 'name' => 'Rangpur', 'bn_name' => 'রংপুর'],

            // ময়মনসিংহ বিভাগ (ID: 8)
            ['id' => 16, 'division_id' => 8, 'name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ'],
        ];
        DB::table('districts')->insert($districts);

        // ৪. উপজেলাসমূহ (Upazilas) ইনসার্ট (কলাম: bn_name)
        $upazilas = [
            // ঢাকা জেলার উপজেলা (District ID: 1)
            ['id' => 1, 'district_id' => 1, 'name' => 'Dhanmondi', 'bn_name' => 'ধানমন্ডি'],
            ['id' => 2, 'district_id' => 1, 'name' => 'Mirpur', 'bn_name' => 'মিরপুর'],
            ['id' => 3, 'district_id' => 1, 'name' => 'Savar', 'bn_name' => 'সাভার'],
            ['id' => 4, 'district_id' => 1, 'name' => 'Gulshan', 'bn_name' => 'গুলশান'],
            ['id' => 5, 'district_id' => 1, 'name' => 'Uttara', 'bn_name' => 'উত্তরা'],

            // গাজীপুর জেলার উপজেলা (District ID: 2)
            ['id' => 6, 'district_id' => 2, 'name' => 'Gazipur Sadar', 'bn_name' => 'গাজীপুর সদর'],
            ['id' => 7, 'district_id' => 2, 'name' => 'Kaliakair', 'bn_name' => 'কালিয়াকৈর'],

            // নারায়ণগঞ্জ জেলার উপজেলা (District ID: 3)
            ['id' => 8, 'district_id' => 3, 'name' => 'Narayanganj Sadar', 'bn_name' => 'নারায়ণগঞ্জ সদর'],
            ['id' => 9, 'district_id' => 3, 'name' => 'Araihazar', 'bn_name' => 'আড়াইহাজার'],

            // চট্টগ্রাম জেলার উপজেলা (District ID: 6)
            ['id' => 10, 'district_id' => 6, 'name' => 'Hathazari', 'bn_name' => 'হাটহাজারী'],
            ['id' => 11, 'district_id' => 6, 'name' => 'Anwara', 'bn_name' => 'আনোয়ারা'],

            // কক্সবাজার জেলার উপজেলা (District ID: 7)
            ['id' => 12, 'district_id' => 7, 'name' => 'Ukhia', 'bn_name' => 'উখিয়া'],
            ['id' => 13, 'district_id' => 7, 'name' => 'Teknaf', 'bn_name' => 'টেকনাফ'],
        ];
        DB::table('upazilas')->insert($upazilas);
    }
}