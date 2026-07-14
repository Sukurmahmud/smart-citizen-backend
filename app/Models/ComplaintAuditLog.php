<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function user(): BelongsTo
    {
        // এখানে User::class হলো আপনার আসল ইউজার বা এডমিন মডেল
        // এবং user_id হলো complaint_audit_logs টেবিলের ফরেন কি (Foreign Key)
        return $this->belongsTo(User::class, 'user_id');
    }
}