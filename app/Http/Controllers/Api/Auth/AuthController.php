<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    /**
     * ওটিপি পাঠানো (Send OTP)
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'] // বাংলাদেশি নম্বর ভ্যালিডেশন
        ]);

        $phone = $request->phone;
        
        // টেস্ট করার সুবিধার্থে ওটিপি ১২৩৪৫৬ জেনারেট হবে (প্রোডাকশনে rand(100000, 999999) হবে)
        $otp = 123456; 

        // ৫ মিনিটের জন্য ওটিপি ক্যাশ-এ সেভ রাখা হলো
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        // এখানে আপনার SMS Gateway API কল করার কোড বসবে

        return response()->json([
            'success' => true,
            'message' => 'আপনার মোবাইলে ৬ ডিজিটের ওটিপি পাঠানো হয়েছে (Test OTP: 123456)'
        ], 200);
    }

    /**
     * ওটিপি ভেরিফাই ও লগইন (Verify OTP & Login)
     */
    /**
     * ওটিপি ভেরিফাই ও লগইন (Verify OTP & Login)
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'otp' => 'required|numeric|digits:6',
        ]);

        // [ডেভেলাপার বাইপাস ট্রিক]: যদি ওটিপি ১২৩৪৫৬ দেওয়া হয়, তবে ক্যাশ চেক ছাড়াই সরাসরি লগইন হবে!
        if ($request->otp == '123456') {
            $cachedOtp = '123456';
        } else {
            $cachedOtp = Cache::get('otp_' . $request->phone);
        }

        // ওটিপি ম্যাচিং চেক
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'ওটিপি সঠিক নয় অথবা এটার মেয়াদ শেষ হয়ে গেছে।'
            ], 422);
        }

        // ওটিপি মিলে গেলে ক্যাশ থেকে ডিলিট করে দেওয়া
        Cache::forget('otp_' . $request->phone);

        // ইউজার আগে থেকে থাকলে লগইন হবে, না থাকলে নতুন ইউজার তৈরি হবে
        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['role' => 'citizen', 'status' => 'active']
        );

        // লারাভেল স্যাঙ্কটাম দিয়ে টোকেন জেনারেট
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'লগইন সফল হয়েছে।',
            'token' => $token,
            'user' => new UserResource($user)
        ], 200);
    }
}