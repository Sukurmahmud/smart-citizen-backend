<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    protected $fillable = ['complaint_id', 'file_path', 'file_type', 'uploaded_by'];

    // রিলেশনশিপ: এই ফাইল বা প্রমাণটি কোন অভিযোগের অংশ
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}