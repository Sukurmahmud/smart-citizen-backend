<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    // ডাটাবেজের টেবিল নাম (লারাভেল সাধারণত complaint_attachments ধরে নেয়, যদি অন্য কিছু হয় তবে নিচে উল্লেখ করতে পারেন)
    // protected $table = 'complaint_attachments';

    protected $fillable = [
        'complaint_id', 
        'file_path', 
        'file_type', 
        'uploaded_by'
    ];

    /**
     * রিলেশনশিপ: এই অ্যাটাচমেন্টটি কোন অভিযোগের অংশ (Inverse Relationship)
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id');
    }
}