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


    // ১. হোমপেজ লোড করার সময় সেশনে এলাকা আছে কি না চেক করা

    // মেইন পাবলিক হোমপেজ (বিভাগ, জেলা, উপজেলা ডাটা সহ)
    public function index(Request $request)
{
    // ১. আগের সব ডাটা (যা স্বাগত পেজে লাগতো) আগের মতোই থাকবে ভাই
    $divisions = \App\Models\Division::all();
    $districts = \App\Models\District::all();
    $upazilas = \App\Models\Upazila::all();

    // ২. সেশনে ইউজারের সিলেক্ট করা এলাকা আছে কি না চেক করছি
    $selectedDistrict = session('user_district_id');
    $selectedUpazila = session('user_upazila_id');

    // ৩. যদি অলরেডি এলাকা সিলেক্ট করা থাকে, তবে শুধু ওই এলাকার অভিযোগগুলো লোড করব
    $complaints = collect(); // ডিফল্ট খালি সংগ্রহ
    if ($selectedDistrict && $selectedUpazila) {
        $complaints = \App\Models\Complaint::with(['attachments'])
            ->where('district_id', $selectedDistrict)
            ->where('upazila_id', $selectedUpazila)
            ->latest()
            ->get();
    }

    // আগের মতোই welcome পেজে সব ডাটা পাস করে দিচ্ছি
    return view('welcome', compact('divisions', 'districts', 'upazilas', 'complaints'));
}

public function selectArea(Request $request)
        {
            $request->validate([
                'district_id' => 'required',
                'upazila_id' => 'required',
            ]);

            // সেশনে এলাকার তথ্য রেখে দেওয়া
            session([
                'user_district_id' => $request->district_id,
                'user_upazila_id' => $request->upazila_id,
            ]);

            return response()->json(['success' => true]);
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
public function create()
    {
        // ফরমে দেখানোর জন্য বিভাগ, জেলা ও উপজেলার ডেটা নিয়ে আসা
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();

        // resources/views/create.blade.php ফাইলটি লোড করবে
        return view('create', compact('divisions', 'districts', 'upazilas'));
    }

    /**
     * ২. ফর্মের সাবমিট করা ডেটা ডাটাবেজে সেভ করার জন্য (POST)
     */
public function store(Request $request)
{
    $request->validate([
        'title'         => 'required|string',
        'description'   => 'required|string',
        'citizen_phone' => 'required|string',
        'division_id'   => 'required|integer',
        'district_id'   => 'required|integer',
        'upazila_id'    => 'required|integer',
        'latitude'      => 'nullable|string', // জিপিএস ডেটা অপশনাল রাখা ভালো
        'longitude'     => 'nullable|string',
    ]);

    // ট্র্যাকিং আইডি ও অভিযোগ সেভ
    $trackingNumber = 'COMP-' . strtoupper(Str::random(6));

   // আপনার কন্ট্রোলারের ভেতর Complaint::create অংশটি এভাবে পরিবর্তন করুন:
$complaint = Complaint::create([
    'tracking_number'  => $trackingNumber,
    'title'            => $request->title,
    'description'      => $request->description,
    
    // ফিক্স: এখানে অবশ্যই citizen_id পাঠাতে হবে
    // আপনার সিস্টেমে যদি লগইন করা ইউজার থাকে তবে auth()->id() দিতে পারেন, 
    // অন্যথায় আপাতত টেস্ট করার জন্য ১ (ডিফল্ট সিডেড আইডি) দিন:
    'citizen_id'       => auth()->id() ?? 1, 
    
    'citizen_phone'    => $request->citizen_phone,
    'division_id'      => $request->division_id,
    'district_id'      => $request->district_id,
    'upazila_id'       => $request->upazila_id,
    'latitude'         => $request->latitude ?? '23.8103', 
    'longitude'        => $request->longitude ?? '90.4125',
    'status'           => 'pending',
]);

    return redirect()->route('complaint.create')->with('success_complaint', 'আপনার অভিযোগটি নিবন্ধিত হয়েছে। নম্বর: ' . $trackingNumber);
}
}