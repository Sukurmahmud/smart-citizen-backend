<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAuditLog extends Model
{
    // ⚠️ এই লাইনটি মিসিং থাকার কারণেই মাস অ্যাসাইনমেন্ট এরর আসছিল
    protected $fillable = [
        'complaint_id', 
        'action_by', 
        'old_status', 
        'new_status', 
        'remarks', 
        'ip_address', 
        'user_agent'
    ];

    // রিলেশনশিপ: এই লগটি কোন কমপ্লেইন এর আন্ডারে তা জানা
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}