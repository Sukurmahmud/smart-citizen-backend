<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tracking_number', 'citizen_id','citizen_phone', 'title', 'description',
        'latitude', 'longitude', 'division_id', 'district_id',
        'upazila_id', 'union_id', 'current_representative_id',
        'escalation_level', 'status', 'citizen_rating', 'citizen_feedback'
    ];

    // নাগরিকের সাথে রিলেশন
    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    // বর্তমান দায়িত্বপ্রাপ্ত প্রতিনিধির সাথে রিলেশন
    public function representative()
    {
        return $this->belongsTo(User::class, 'current_representative_id');
    }

    // বিভাগ, জেলা, উপজেলা ও ইউনিয়নের সাথে রিলেশন
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class, 'upazila_id');
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

    /**
     * 🟢 যুক্ত করা হলো: কমেন্ট বা মতামত রিলেশনশিপ
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'complaint_id', 'id')->latest();
    }

    /**
     * ডাইনামিক গুগল ম্যাপ লিংক জেনারেট করার অ্যাক্সেসর
     */
    public function getGoogleMapLinkAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
        }
        
        return null;
    }
}