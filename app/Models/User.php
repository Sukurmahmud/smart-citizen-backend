<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ⚠️ ১. এই লাইনটি অবশ্যই উপরে থাকতে হবে

class User extends Authenticatable
{
    use HasApiTokens, Notifiable; // ⚠️ ২. ক্লাসের ভেতরে অবশ্যই 'HasApiTokens' যোগ করতে হবে

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'role',
        'division_id', 'district_id', 'upazila_id', 'union_id',
        'nid_number', 'is_nid_verified', 'status', 'fcm_token'
    ];

    // ... আপনার বাকি কোড নিচে যেভাবে আছে সেভাবেই থাকবে

    
        // রিলেশনশিপ: একজন ইউজারের অনেকগুলো কমপ্লেইন থাকতে পারে
        public function complaints()
        {
            return $this->hasMany(Complaint::class, 'citizen_id');
        }

        // রিলেশনশিপ: একজন জনপ্রতিনিধির আন্ডারে অনেক কমপ্লেইন অ্যাসাইন থাকতে পারে
        public function assignedComplaints()
        {
            return $this->hasMany(Complaint::class, 'current_representative_id');
        }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

