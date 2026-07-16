<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    protected $complaintService;

    // কনস্ট্রাক্টরের মাধ্যমে সার্ভিস ডিপেন্ডেন্সি ইনজেকশন
    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }

    /**
     * এপিআই-এর মাধ্যমে নতুন অভিযোগ রেজিস্টার করা
     */
    /**
     * এপিআই-এর মাধ্যমে নতুন অভিযোগ রেজিস্টার করা
     */
    public function store(Request $request)
    {
        // ১. কঠোর প্রোডাকশন লেভেল ভ্যালিডেশন (সমন্বিত ও নমনীয় করা হলো)
        $validatedData = $request->validate([
            'title'         => 'required|string|max:150',
            'description'   => 'required|string',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'division_id'   => 'required|exists:divisions,id',
            'district_id'   => 'required|exists:districts,id',
            'upazila_id'    => 'required|exists:upazilas,id',
            'union_id'      => 'nullable|exists:unions,id',
            'citizen_phone' => 'nullable|string|max:15',
            
            // 🟢 ফিক্স: ফর্মের 'evidence' (সিঙ্গেল) অথবা এপিআই এর 'attachments' (অ্যারে) যেকোনো একটি থাকলেই চলবে
            'evidence'      => 'nullable|file|mimes:jpg,jpeg,png,mp4,mkv,avi,mov|max:20480',
            'attachments'   => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mkv,avi,mov|max:20480',
        ]);

        // যেকোনো একটি ফাইল অবশ্যই সংযুক্ত থাকতে হবে—এই নিয়ম বজায় রাখা হলো
        if (!$request->hasFile('evidence') && !$request->hasFile('attachments')) {
            return response()->json([
                'success' => false,
                'message' => 'কমপক্ষে একটি ডিজিটাল প্রমাণ (ছবি বা ভিডিও) সংযুক্ত করা বাধ্যতামূলক।'
            ], 422);
        }

        try {
            // ২. সার্ভিস লেয়ারের মাধ্যমে ডাটাবেজে মূল কমপ্লেইন সেভ
            $citizenId = Auth::id() ?? null;
            $complaint = $this->complaintService->storeComplaint($validatedData, $citizenId);

            // ৩. ফাইল প্রসেসিং এবং আপলোড লজিক
            $filesToUpload = [];

            // ক. যদি ফর্ম থেকে 'evidence' নামে সিঙ্গেল ফাইল আসে
            if ($request->hasFile('evidence')) {
                $filesToUpload[] = $request->file('evidence');
            }

            // খ. যদি এপিআই থেকে 'attachments' নামে মাল্টিপল ফাইল আসে
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filesToUpload[] = $file;
                }
            }

            // গ. সব ফাইল লুপ চালিয়ে স্টোরেজে এবং ডাটাবেজে সেভ করা
            foreach ($filesToUpload as $file) {
                // ফাইলটি storage/app/public/complaints/attachments ডিরেক্টরিতে স্টোর হবে
                $path = $file->store('complaints/attachments', 'public'); 
                
                // এক্সটেনশন চেক করে ফাইল টাইপ নির্ধারণ করা
                $extension = strtolower($file->getClientOriginalExtension());
                $videoExtensions = ['mp4', 'mkv', 'avi', 'mov', 'webm', 'ogg'];
                $fileType = in_array($extension, $videoExtensions) ? 'video' : 'image';

                // অ্যাটাচমেন্ট টেবিলে এন্ট্রি দেওয়া
                // অ্যাটাচমেন্ট টেবিলে এন্ট্রি দেওয়া
                ComplaintAttachment::create([
                    'complaint_id' => $complaint->id,
                    'file_path'    => $path,
                    'file_type'    => $fileType,
                    
                    // 🟢 ফিক্স: 'anonymous'-এর পরিবর্তে 'citizen' পাঠানো হচ্ছে যাতে ডাটাবেজ রিজেক্ট না করে
                    'uploaded_by'  => $citizenId ? 'citizen' : 'citizen' 
                ]);
            }

            // ৪. সফল রেসপন্স রিটার্ন
            return response()->json([
                'success'         => true,
                'message'         => 'আপনার অভিযোগটি সফলভাবে রেজিস্টার হয়েছে।',
                'tracking_number' => $complaint->tracking_number,
                'complaint_id'    => $complaint->id
            ], 201);

        } catch (\Exception $e) {
            Log::error('Complaint Submission Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'দুঃখিত, সার্ভার সমস্যার কারণে অভিযোগটি জমা নেওয়া সম্ভব হয়নি।',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    /**
     * মন্তব্য বা কমেন্ট সাবমিট করা
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment_text' => 'required|string|max:500',
        ]);

        $complaint = Complaint::where('id', $id)
                              ->orWhere('tracking_number', $id)
                              ->first();

        if (!$complaint) {
            return redirect()->back()->with('error', 'দুঃখিত, এই অভিযোগটি খুঁজে পাওয়া যায়নি।');
        }

        // লগইন না থাকলে 'অজ্ঞাত নাগরিক' সেট হবে
        $userName = auth()->check() ? (auth()->user()->name ?? 'নিবন্ধিত নাগরিক') : 'অজ্ঞাত নাগরিক';

        \App\Models\Comment::create([
            'complaint_id' => $complaint->id,
            'comment_text' => $request->comment_text,
            'user_name'    => $userName,
        ]);

        return redirect()->back()->with('success', 'আপনার মতামত সফলভাবে পোস্ট হয়েছে।');
    }
}