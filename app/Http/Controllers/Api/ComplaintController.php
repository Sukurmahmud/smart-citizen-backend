<?php

namespace App\Http\Controllers\Api; // ⚠️ নেমস্পেস একদম নিখুঁত রাখা হয়েছে

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    protected $complaintService;

    // কনস্ট্রাক্টরের মাধ্যমে সার্ভিস লেয়ার কল করা
    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }

    /**
     * নতুন অভিযোগ জমা নেওয়া
     */
    public function store(Request $request)
    {
        // প্রোডাকশন লেভেল কড়া ভ্যালিডেশন
        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'union_id' => 'nullable|exists:unions,id',
            'attachments' => 'required|array|min:1', // অন্তত ১টি ছবি বা প্রমাণ বাধ্যতামূলক
            'attachments.*' => 'required|file|mimes:jpg,jpeg,png,mp4|max:20480', // সর্বোচ্চ ২০ মেগাবাইট
        ]);

        // ১. সার্ভিস লেয়ারের মাধ্যমে কমপ্লেইন ডাটাবেজে সেভ
        $citizenId = Auth::id(); // লগইন থাকা নাগরিকের আইডি
        $complaint = $this->complaintService->storeComplaint($validatedData, $citizenId);

        // ২. ফাইল বা প্রমাণপত্র আপলোড হ্যান্ডেল করা
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // storage/app/public/complaints এ সেভ হবে
                $path = $file->store('complaints/attachments', 'public'); 
                
                // ফাইল টাইপ ডিটেক্ট করা
                $extension = strtolower($file->getClientOriginalExtension());
                $fileType = in_array($extension, ['mp4', 'mkv', 'avi']) ? 'video' : 'image';

                ComplaintAttachment::create([
                    'complaint_id' => $complaint->id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'uploaded_by' => 'citizen'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'আপনার অভিযোগটি সফলভাবে রেজিস্টার হয়েছে।',
            'tracking_number' => $complaint->tracking_number
        ], 201); // ⚠️ এখানে আগের কোডে ২১ টাইপো ছিল, যা এবার সফলভাবে ২০১ (Created) করা হয়েছে
    }
}