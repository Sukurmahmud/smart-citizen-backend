<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $fillable = ['district_id', 'name', 'bn_name'];

    // রিলেশনশিপ: উপজেলাটি কোন জেলার অধীনে তা জানা
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // রিলেশনশিপ: একটি উপজেলার অধীনে অনেকগুলো ইউনিয়ন/ওয়ার্ড থাকে
    public function unions()
    {
        return $this->hasMany(Union::class);
    }
}