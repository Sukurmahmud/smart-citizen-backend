<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['name', 'bn_name'];

    // রিলেশনশিপ: একটি বিভাগের অধীনে অনেকগুলো জেলা থাকে
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}