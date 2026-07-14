<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * ড্যাশবোর্ড হোম পেজ
     */
    /**
     * ড্যাশবোর্ড হোম পেজ
     */
    public function index()
    {
        $user = Auth::user();
        $query = Complaint::query();

        // যদি সুপার অ্যাডমিন না হয়ে সাধারণ জনপ্রতিনিধি হয়, তবে শুধু তার এলাকার কমপ্লেইন দেখাবে
        if ($user->role === 'representative') {
            if ($user->union_id) {
                $query->where('union_id', $user->union_id);
            } elseif ($user->upazila_id) {
                $query->where('upazila_id', $user->upazila_id);
            } elseif ($user->district_id) {
                $query->where('district_id', $user->district_id);
            }
        }

        // ড্যাশবোর্ডের কাউন্টার কার্ডের জন্য ডেটা হিসেব করা
        $data['total_complaints'] = $query->count();
        $data['pending_complaints'] = (clone $query)->where('status', 'pending')->count();
        
        // ফিক্স: 'resolved' এর পরিবর্তে 'solved' স্ট্যাটাস কাউন্ট করা হচ্ছে
        $data['resolved_complaints'] = (clone $query)->where('status', 'solved')->count();
        
        // লেটেস্ট ১০টি কমপ্লেইন পেজিনেশন সহ নেওয়া
        $data['complaints'] = $query->latest()->paginate(10);

        return view('dashboard.index', $data);
    }

    /**
     * অভিযোগের বিস্তারিত এবং স্ট্যাটাস পরিবর্তনের পেজ
     */
    public function show($id)
    {
        $complaint = Complaint::with(['attachments', 'auditLogs.user', 'division', 'district', 'upazila'])->findOrFail($id);
        return view('dashboard.show', compact('complaint'));
    }

    /**
     * কর্মকর্তার মাধ্যমে স্ট্যাটাস আপডেট এবং অডিট লগ তৈরি
     */
    public function updateStatus(Request $request, $id)
    {
        // ফিক্স: মাইগ্রেশন অনুযায়ী 'in_progress' এর বদলে 'investigating' এবং 'resolved' এর বদলে 'solved' ব্যবহার করুন।
        $request->validate([
            'status' => 'required|in:investigating,solved,rejected', 
            'remarks' => 'required|string|min:10'
        ]);

        $complaint = Complaint::findOrFail($id);
        $oldStatus = $complaint->status;

        // স্ট্যাটাস আপডেট
        $complaint->update([
            'status' => $request->status // এখন ডাটাবেজ এটি খুশিমনে গ্রহণ করবে!
        ]);

        // অডিট লগ তৈরি
        ComplaintAuditLog::create([
            'complaint_id' => $complaint->id,
            //'user_id'      => Auth::id(),
            'action_by'    => Auth::id() ?? 1,
            'old_status'   => $oldStatus,
            'new_status'   => $request->status,
            'remarks'      => $request->remarks,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent()
        ]);

        return redirect()->back()->with('success', 'অভিযোগের স্ট্যাটাস সফলভাবে আপডেট হয়েছে এবং অডিট ট্রেইলে লক করা হয়েছে।');
    }
}