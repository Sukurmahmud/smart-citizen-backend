<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // ১. ডাটাবেজে ডাটা ইনসার্ট করার পারমিশন
    protected $fillable = [
        'complaint_id', 
        'comment_text', 
        'user_name' 
    ];

    /**
     * ২. রিলেশনশিপ ঠিক করা হলো (belongsTo):
     * এই কমেন্টটি কোন অভিযোগের (Complaint) আন্ডারে করা হয়েছে তা জানার জন্য
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id');
    }
}