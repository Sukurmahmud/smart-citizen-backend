<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Attachment;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ComplaintAttachment;

class HomeController extends Controller
{
    // মেইন পাবলিক হোমপেজ (বিভাগ, জেলা, উপজেলা ডাটা সহ)
    public function index()
    {
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        return view('welcome', compact('divisions', 'districts', 'upazilas'));
    }

    // কমপ্লেইন ট্র্যাক করার লজিক (আগের মতোই)
    public function track(Request $request)
    {
        $request->validate(['tracking_number' => 'required|string']);

        $complaint = Complaint::with(['auditLogs', 'division', 'district', 'upazila'])
            ->where('tracking_number', $request->tracking_number)
            ->first();

        if (!$complaint) {
            return redirect()->back()->with('error', 'দুঃখিত, এই ট্র্যাকিং নম্বরের কোনো অভিযোগ পাওয়া যায়নি।')->withInput();
        }

        // ফর্ম দেখানোর জন্য এগুলোও পাস করতে হবে
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();

        return view('welcome', compact('complaint', 'divisions', 'districts', 'upazilas'));
    }

    // ওয়েবসাইট থেকে সরাসরি অভিযোগ জমা নেওয়ার লজিক
    public function storeComplaint(Request $request)
    {
        // ফর্ম ভ্যালিডেশন
        $request->validate([
            'citizen_phone'   => 'required|string',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'division_id'     => 'required|integer',
            'district_id'     => 'required|integer',
            'upazila_id'      => 'required|integer',
            'evidence'        => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:51200', // max 50MB
        ]);

        // ১. ট্র্যাকিং নম্বর জেনারেট করা
        $trackingNumber = 'COMP-' . strtoupper(Str::random(6));

        // ২. অভিযোগ ডাটাবেজে সেভ করা
        // অভিযোগ ডাটাবেজে সেভ করা
            // অভিযোগ ডাটাবেজে সেভ করা (HomeController.php এর ভেতর)
            // অভিযোগ ডাটাবেজে সেভ করা (HomeController.php এর ভেতর)
            // অভিযোগ ডাটাবেজে সেভ করা (HomeController.php এর ভেতর)
// অভিযোগ ডাটাবেজে সেভ করা (HomeController.php এর ভেতর)
// অভিযোগ ডাটাবেজে সেভ করা (HomeController.php এর ভেতর)
$complaint = Complaint::create([
    'tracking_number'  => $trackingNumber,
    'title'            => $request->title,
    'description'      => $request->description,
    
    // ফিক্স: মোবাইল নম্বর সরাসরি আইডিতে না পাঠিয়ে, ডাটাবেজের আইডি ১ (যে কর্মকর্তা আমরা সিড করেছি) তাকেই অ্যাসাইন করে দিচ্ছি।
    // আর নাগরিকের আসল ফোন নম্বরটি আমাদের মডেলে থাকা 'citizen_phone' ফিল্ডে স্ট্রিং হিসেবে পাঠিয়ে দিচ্ছি।
    'citizen_id'       => 1, 
    'citizen_phone'    => $request->citizen_phone, // আপনার মডেলে এটি $fillable আছে, তাই সুন্দর কাজ করবে
    
    // ল্যাটিচিউড ও লঙ্গিচিউড
    'latitude'         => '23.8103', 
    'longitude'        => '90.4125', 
    
    // বিভাগ, জেলা ও উপজেলা (যা এখন ডাটাবেজে আইডি ১ হিসেবে ঢাকা ও ধানমন্ডি সাকসেসফুলি আছে)
    'division_id'      => $request->division_id,
    'district_id'      => $request->district_id,
    'upazila_id'       => $request->upazila_id,
    'escalation_level' => 1,
    'status'           => 'pending',
]);

        // ৩. যদি কোনো ছবি বা ভিডিও ফাইল আপলোড করে
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $path = $file->store('complaints', 'public');
            $fileType = in_array($file->getClientOriginalExtension(), ['mp4']) ? 'video' : 'image';

            ComplaintAttachment::create([
                'complaint_id' => $complaint->id,
                'file_path'    => $path,
                'file_type'    => $fileType,
                
                // ফিক্স: সংখ্যা '1' এর বদলে আপনার ডাটাবেজের CHECK শর্ত অনুযায়ী স্ট্রিং পাস করুন
                'uploaded_by'  => 'citizen', // যদি কাজ না করে, তবে 'representative' দিয়ে দেখতে পারেন ভাই!
            ]);
        }

        // সফলতার মেসেজ সহ ট্র্যাকিং আইডি ব্যাক করা
        return redirect()->route('home')->with('success_complaint', 'আপনার অভিযোগটি সফলভাবে রেজিস্টার্ড হয়েছে! আপনার গোপন ট্র্যাকিং নম্বরটি সংরক্ষণ করুন: ' . $trackingNumber);
    }
}