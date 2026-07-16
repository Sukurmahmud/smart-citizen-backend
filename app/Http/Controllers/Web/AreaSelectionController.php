<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Upazila;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AreaSelectionController extends Controller
{
    /**
     * বিভাগ অনুযায়ী জেলাগুলো খোঁজা (AJAX) - ফিক্সড
     */
    public function getDistricts($division_id)
    {
        // ডাটাবেজে name_bn কলাম না থাকলে শুধুমাত্র id এবং name নিয়ে আসা হচ্ছে
        $districts = District::where('division_id', $division_id)->get(['id', 'name']);
        return response()->json($districts);
    }

    /**
     * জেলা অনুযায়ী উপজেলাগুলো খোঁজা (AJAX) - ফিক্সড
     */
    public function getUpazilas($district_id)
    {
        // ডাটাবেজে name_bn কলাম না থাকলে শুধুমাত্র id এবং name নিয়ে আসা হচ্ছে
        $upazilas = Upazila::where('district_id', $district_id)->get(['id', 'name']);
        return response()->json($upazilas);
    }

    /**
     * ইউজারের সিলেক্ট করা এলাকা সেশনে সেভ করা
     */
    public function saveSelection(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'upazila_id'  => 'required|exists:upazilas,id',
        ]);

        session(['user_area' => [
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_id'  => $request->upazila_id,
        ]]);

        return redirect()->back()->with('success_area', 'আপনার এলাকা সফলভাবে সেট করা হয়েছে!');
    }

    /**
     * জিপিএস কোঅর্ডিনেট দিয়ে অটো-লোকেশন ডিটেক্ট করা (AJAX API)
     */
    public function detectLocation(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        if (!$lat || !$lon) {
            return response()->json(['success' => false, 'message' => 'Coordinates missing']);
        }

        // ১. OpenStreetMap API কল (বাংলা ও ইংরেজি দুই ভাষাই রিকোয়েস্ট করা হয়েছে)
        $response = Http::withHeaders([
            'User-Agent' => 'SmartCitizenPortal/1.0'
        ])->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&accept-language=bn,en");

        if ($response->failed()) {
            return response()->json(['success' => false, 'message' => 'Reverse geocoding failed']);
        }

        $address = $response->json()['address'] ?? null;

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'No address found']);
        }

        // 🔴 [ডিবাগিং লগ ১] API থেকে পাওয়া আসল ঠিকানা লগ ফাইলে সেভ করা হচ্ছে
        Log::info("=== GPS API Address Data ===", $address);

        // ২. ওপেনস্ট্রিটম্যাপ থেকে জেলা ও উপজেলার বিভিন্ন সম্ভাব্য কি-ওয়ার্ডের ডেটা নেওয়া
        $districtEn = $address['state_district'] ?? $address['county'] ?? null;
        $upazilaEn  = $address['suburb'] ?? $address['city_district'] ?? $address['municipality'] ?? null;
        
        $districtBn = $address['district'] ?? null;
        $upazilaBn  = $address['village'] ?? $address['town'] ?? null;

        // ৩. ইংরেজি নামের বাড়তি অংশ পরিষ্কার করা (যেমন: "Dhaka District" থেকে "Dhaka")
        $cleanDistrictEn = trim(str_replace(['District', 'Zila', ' zila', ' district'], '', $districtEn));
        $cleanUpazilaEn  = trim(str_replace(['Upazila', 'Thana', ' upazila', ' thana'], '', $upazilaEn));

        // 🔴 [ডিবাগিং লগ ২] ক্লিন করার পর আমরা কী নামে খুঁজছি তা লগ করা হচ্ছে
        Log::info("=== Cleaned Search Terms ===", [
            'Clean District En' => $cleanDistrictEn,
            'Clean Upazila En' => $cleanUpazilaEn,
            'District Bn' => $districtBn,
            'Upazila Bn' => $upazilaBn
        ]);

        // ৪. ডাটাবেজে জেলা ম্যাচ করানো (শুধুমাত্র 'name' কলাম দিয়ে কুয়েরি করা হচ্ছে)
        $district = District::query()
            ->where('name', 'LIKE', "%{$cleanDistrictEn}%")
            ->first();

        // 🔴 [ডিবাগিং লগ ৩] জেলা মিলল কি না তা লগ করা
        Log::info("=== DB District Result ===", [
            'Found District' => $district ? $district->name : 'NOT FOUND'
        ]);

        // ৫. ডাটাবেজে উপজেলা ম্যাচ করানো (স্মার্ট আংশিক ম্যাচিং লজিক সহ)
        $upazila = null;
        if ($district) {
            // জিপিএস নামের প্রথম শব্দটা আলাদা করা (যেমন: "Mirpur 10" থেকে "Mirpur" নেওয়ার জন্য)
            $firstWordOfUpazila = explode(' ', $cleanUpazilaEn)[0] ?? $cleanUpazilaEn;

            $upazila = Upazila::query()
                ->where('district_id', $district->id)
                ->where(function($query) use ($cleanUpazilaEn, $firstWordOfUpazila) {
                    $query->where('name', 'LIKE', "%{$cleanUpazilaEn}%")
                          ->orWhere('name', 'LIKE', "%{$firstWordOfUpazila}%");
                })
                ->first();
        }

        // 🔴 [ডিবাগিং লগ ৪] উপজেলা মিলল কি না তা লগ করা
        Log::info("=== DB Upazila Result ===", [
            'Found Upazila' => $upazila ? $upazila->name : 'NOT FOUND'
        ]);

        // ৬. ফ্রন্টএন্ডে ফলাফল পাঠানো
        if ($district && $upazila) {
            return response()->json([
                'success'       => true,
                'division_id'   => $district->division_id,
                'district_id'   => $district->id,
                'upazila_id'    => $upazila->id,
                'district_name' => $district->name,
                'upazila_name'  => $upazila->name,
            ]);
        }

        // যদি অন্তত জেলা মিলে যায়, তবে জেলা সিলেক্ট করে উপজেলা ইউজারকে বেছে নিতে বলবে
        if ($district) {
            return response()->json([
                'success'       => true,
                'division_id'   => $district->division_id,
                'district_id'   => $district->id,
                'upazila_id'    => null,
                'district_name' => $district->name,
                'upazila_name'  => 'ম্যানুয়ালি সিলেক্ট করুন',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No DB match found']);
    }
}