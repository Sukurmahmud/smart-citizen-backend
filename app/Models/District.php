<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['division_id', 'name', 'bn_name'];

    // রিলেশনশিপ: জেলাটি কোন বিভাগের অধীনে তা জানা
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    // রিলেশনশিপ: একটি জেলার অধীনে অনেকগুলো উপজেলা থাকে
    public function upazilas()
    {
        return $this->hasMany(Upazila::class);
    }
}