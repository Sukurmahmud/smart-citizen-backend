<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AreaSelectionController extends Controller
{
    /**
     * এলাকা নির্বাচন পেজ দেখানো
     */
    public function showSelectionForm()
    {
        $divisions = Division::all();
        return view('select-area', compact('divisions'));
    }

    /**
     * ইউজারের সিলেক্ট করা এলাকা সেশনে সংরক্ষণ করা
     */
    public function saveArea(Request $request)
    {
        $request->validate([
            'division_id' => 'required|integer',
            'district_id' => 'required|integer',
            'upazila_id'  => 'required|integer',
            'union_id'    => 'nullable|integer', 
        ]);

        session([
            'user_area' => [
                'division_id' => $request->division_id,
                'district_id' => $request->district_id,
                'upazila_id'  => $request->upazila_id,
                'union_id'    => $request->union_id,
            ]
        ]);

        return redirect()->route('home'); 
    }

    /**
     * বিভাগ অনুযায়ী জেলা পাঠানো (AJAX) - name_bn ক্র্যাশ এড়াতে সেফ কোড
     */
    public function getDistricts($division_id)
    {
        // ডাটাবেজে name_bn কলামের অনুপস্থিতি বা যেকোনো ভুলের জন্য এটি ক্র্যাশ করবে না
        $districts = District::where('division_id', $division_id)->get();
        return response()->json($districts);
    }

    /**
     * জেলা অনুযায়ী উপজেলা পাঠানো (AJAX)
     */
    public function getUpazilas($district_id)
    {
        $upazilas = Upazila::where('district_id', $district_id)->get();
        return response()->json($upazilas);
    }

    /**
     * উপজেলা অনুযায়ী ইউনিয়ন পাঠানো (AJAX)
     */
    public function getUnions($upazila_id)
    {
        if (class_exists(\App\Models\Union::class)) {
            $unions = Union::where('upazila_id', $upazila_id)->get();
            return response()->json($unions);
        }
        return response()->json([]);
    }

    /**
     * জিপিএস কোঅর্ডিনেট দিয়ে অটো-লোকেশন ডিটেক্ট করা (AJAX API) - নতুন যুক্ত করা হলো
     */
    public function detectLocation(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        if (!$lat || !$lon) {
            return response()->json(['success' => false, 'message' => 'Coordinates missing']);
        }

        // ১. OpenStreetMap API কল
        $response = Http::withHeaders([
            'User-Agent' => 'SmartCitizenPortal/1.0'
        ])->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&accept-language=en");

        if ($response->failed()) {
            return response()->json(['success' => false, 'message' => 'Reverse geocoding failed']);
        }

        $address = $response->json()['address'] ?? null;

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'No address found']);
        }

        // API ডাটা লগ ফাইলে সেভ করা হচ্ছে
        Log::info("=== GPS API Address Data ===", $address);

        // ২. জেলা ও উপজেলা খোঁজা
        $districtEn = $address['state_district'] ?? $address['county'] ?? $address['district'] ?? null;
        $upazilaEn  = $address['suburb'] ?? $address['city_district'] ?? $address['municipality'] ?? $address['town'] ?? $address['village'] ?? null;

        $cleanDistrictEn = trim(str_replace(['District', 'Zila', ' zila', ' district'], '', $districtEn));
        $cleanUpazilaEn  = trim(str_replace(['Upazila', 'Thana', ' upazila', ' thana'], '', $upazilaEn));

        Log::info("=== Cleaned Search Terms ===", [
            'Clean District En' => $cleanDistrictEn,
            'Clean Upazila En' => $cleanUpazilaEn
        ]);

        // ৩. ডাটাবেজে জেলা ম্যাচিং
        $district = District::where('name', 'LIKE', "%{$cleanDistrictEn}%")->first();

        Log::info("=== DB District Result ===", [
            'Found District' => $district ? $district->name : 'NOT FOUND'
        ]);

        // ৪. ডাটাবেজে উপজেলা ম্যাচিং
        $upazila = null;
        if ($district) {
            $firstWordOfUpazila = explode(' ', $cleanUpazilaEn)[0] ?? $cleanUpazilaEn;

            $upazila = Upazila::where('district_id', $district->id)
                ->where(function($query) use ($cleanUpazilaEn, $firstWordOfUpazila) {
                    $query->where('name', 'LIKE', "%{$cleanUpazilaEn}%")
                          ->orWhere('name', 'LIKE', "%{$firstWordOfUpazila}%");
                })
                ->first();
        }

        Log::info("=== DB Upazila Result ===", [
            'Found Upazila' => $upazila ? $upazila->name : 'NOT FOUND'
        ]);

        // ৫. রেসপন্স ব্যাক করা
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