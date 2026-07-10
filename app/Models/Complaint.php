<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tracking_number', 'citizen_id', 'title', 'description',
        'latitude', 'longitude', 'division_id', 'district_id',
        'upazila_id', 'union_id', 'current_representative_id',
        'escalation_level', 'status', 'citizen_rating', 'citizen_feedback'
    ];

    // নাগরিকের সাথে রিলেশন
    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    // বর্তমান দায়িত্বপ্রাপ্ত প্রতিনিধির সাথে রিলেশন
    public function representative()
    {
        return $this->belongsTo(User::class, 'current_representative_id');
    }

    // জেলার সাথে রিলেশন
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // প্রমাণপত্র বা অ্যাটাচমেন্টের সাথে রিলেশন
    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    // অডিট লগের সাথে রিলেশন
    public function auditLogs()
    {
        return $this->hasMany(ComplaintAuditLog::class);
    }
}