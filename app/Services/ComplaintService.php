<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use Illuminate\Support\Str;

class ComplaintService
{
    /**
     * ডাটাবেজে নতুন অভিযোগ তৈরি করা
     *
     * @param array $data
     * @param int|null $citizenId
     * @return Complaint
     */
    public function storeComplaint(array $data, $citizenId = null)
    {
        // ডাইনামিক ট্র্যাকিং নম্বর জেনারেট করা (যেমন: BD-2026-DHAKA-123456)
        $trackingNumber = $this->generateTrackingNumber($data);

        return Complaint::create([
            'tracking_number'           => $trackingNumber,
            'citizen_id'                => $citizenId,
            'citizen_phone'             => $data['citizen_phone'] ?? (auth()->user()->phone ?? '01XXXXXXXXX'),
            'title'                     => $data['title'],
            'description'               => $data['description'],
            'latitude'                  => $data['latitude'],
            'longitude'                 => $data['longitude'],
            
            // 🟢 এই আইডিগুলো ডাটাবেজে সেভ হওয়া নিশ্চিত করা হলো
            'division_id'               => $data['division_id'],
            'district_id'               => $data['district_id'],
            'upazila_id'                => $data['upazila_id'],
            'union_id'                  => $data['union_id'] ?? null,
            
            'escalation_level'          => 1,
            'status'                    => 'pending',
        ]);
    }

    /**
     * এলাকার নামের ওপর ভিত্তি করে ট্র্যাকিং নম্বর তৈরি করা
     */
    private function generateTrackingNumber(array $data)
    {
        $year = date('Y'); // বর্তমান বছর (২০২৬)
        
        // জেলার নামের প্রথম ৩ অক্ষর নেওয়া (যেমন: Gazipur -> GAZ)
        $district = District::find($data['district_id']);
        $districtCode = $district ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $district->name ?? 'GEN'), 0, 3)) : 'BD';
        
        // ইউনিক ৬ ডিজিটের র‍্যান্ডম নম্বর
        $randomNumber = rand(100000, 999999);

        return "BD-{$year}-{$districtCode}-{$randomNumber}";
    }
}