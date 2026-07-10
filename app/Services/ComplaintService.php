<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\ComplaintAuditLog;
use Illuminate\Support\Facades\Auth;

class ComplaintService
{
    /**
     * নতুন অভিযোগ তৈরি এবং অডিট লগ এন্ট্রি
     */
    public function storeComplaint(array $data, $citizenId)
    {
        $data['citizen_id'] = $citizenId;
        $data['status'] = 'pending';
        
        $complaint = Complaint::create($data);

        // প্রথম অডিট লগ তৈরি (অভিযোগ দাখিল)
        ComplaintAuditLog::create([
            'complaint_id' => $complaint->id,
            'action_by' => $citizenId,
            'old_status' => null,
            'new_status' => 'pending',
            'remarks' => 'অভিযোগ সফলভাবে দাখিল করা হয়েছে।',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return $complaint;
    }
}