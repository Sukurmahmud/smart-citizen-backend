<?php

namespace App\Observers;

use App\Models\Complaint;
use App\Models\District;

class ComplaintObserver
{
    /**
     * Handle the Complaint "creating" event.
     */
    public function creating(Complaint $complaint): void
    {
        $year = now()->format('Y'); // ২০২৬
        
        // ডিস্ট্রিক্ট কোড বের করা (যেমন: Dhaka হলে DHK)
        $district = District::find($complaint->district_id);
        $districtCode = $district ? strtoupper(substr($district->name, 0, 3)) : 'GEN';
        
        // সর্বশেষ আইডি ট্র্যাক করে সিরিয়াল নম্বর জেনারেট করা
        $lastComplaint = Complaint::where('district_id', $complaint->district_id)
                                  ->whereYear('created_at', $year)
                                  ->latest('id')
                                  ->first();

        $nextSerial = $lastComplaint ? ((int) substr($lastComplaint->tracking_number, -6)) + 1 : 1;
        $paddedSerial = str_pad($nextSerial, 6, '0', STR_PAD_LEFT);

        // ফাইনাল ফরম্যাট: BD-2026-DHK-000001
        $complaint->tracking_number = "BD-{$year}-{$districtCode}-{$paddedSerial}";
    }
}