<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * লগইন পেজ ভিউ করা
     */
    public function showLoginForm()
    {
        // ইউজার যদি অলরেডি লগইন থাকে, তাকে সরাসরি ড্যাশবোর্ডে পাঠিয়ে দেবে
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('auth.login');
    }

    /**
     * লগইন রিকোয়েস্ট প্রসেস করা
     */
    public function login(Request $request)
    {
        // ইনপুট ভ্যালিডেশন
        $credentials = $request->validate([
            'phone' => 'required|string', // আমরা ফোন নম্বর ও পাসওয়ার্ড দিয়ে অ্যাডমিন লগইন করাচ্ছি
            'password' => 'required|string',
        ]);

        // রিমেম্বার মি অপশন
        $remember = $request->has('remember');

        // ক্রেডেনশিয়াল চেক এবং লগইন সেশন তৈরি
        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            // চেক করা হচ্ছে ইউজারটি আসলেই অ্যাডমিন বা রিপ্রেজেন্টেটিভ কি না
            if (in_array(Auth::user()->role, ['super_admin', 'representative'])) {
                return redirect()->intended(route('dashboard.index'));
            }

            // যদি কোনো সাধারণ সিটিজেন ব্রাউজার দিয়ে ঢোকার চেষ্টা করে, তাকে তাড়িয়ে দেওয়া হবে
            Auth::logout();
            return redirect()->back()->withErrors([
                'phone' => 'দুঃখিত, আপনার এই প্যানেলে অ্যাক্সেস করার অনুমতি নেই।',
            ]);
        }

        // লগইন ব্যর্থ হলে এরর ব্যাক করা
        return redirect()->back()->withErrors([
            'phone' => 'প্রদত্ত ফোন নম্বর অথবা পাসওয়ার্ডটি সঠিক নয়।',
        ])->withInput($request->only('phone'));
    }

    /**
     * লগআউট সিস্টেম
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'আপনি সফলভাবে লগআউট করেছেন।');
    }
}