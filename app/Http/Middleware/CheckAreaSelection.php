<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAreaSelection
{
    public function handle(Request $request, Closure $next): Response
    {
        // যদি ইউজার অলরেডি এলাকা সিলেকশন পেজে যাওয়ার চেষ্টা করে বা ফর্ম সাবমিট করে, তবে লুপ এড়াতে রিকোয়েস্ট পাস করতে হবে
        if ($request->routeIs('area.select') || $request->routeIs('area.save')) {
            return $next($request);
        }

        // সেশনে যদি 'user_area' সেট করা না থাকে, তবে তাকে এলাকা সিলেকশন পেজে রিডাইরেক্ট করো
        if (!session()->has('user_area')) {
            return redirect()->route('area.select');
        }

        return $next($request);
    }
}