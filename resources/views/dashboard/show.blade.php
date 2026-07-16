@extends('layouts.admin')

@content('content')
<div class="container mx-auto max-w-6xl">
    <div class="mb-4">
        <a href="{{ route('dashboard.index') }}" class="text-sm font-medium text-green-800 hover:text-green-900 flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরে যান
        </a>
    </div>
    <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-100">
    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-map-location-dot text-rose-500"></i> ঘটনাস্থলের অবস্থান (GPS)
    </h3>

    @if($complaint->latitude && $complaint->longitude)
        <!-- ১. সরাসরি ড্যাশবোর্ডের ভেতরে লাইভ ম্যাপ প্রিভিউ -->
        <div class="rounded-2xl overflow-hidden border border-slate-200 h-64 mb-4">
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                src="https://maps.google.com/maps?q={{ $complaint->latitude }},{{ $complaint->longitude }}&hl=bn&z=16&amp;output=embed">
            </iframe>
        </div>

        <!-- ২. গুগল ম্যাপস অ্যাপে নেভিগেট করার বাটন -->
        <a href="{{ $complaint->google_map_link }}" target="_blank" 
           class="w-full inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-rose-100">
            <i class="fa-solid fa-location-arrow"></i> গুগল ম্যাপসে নেভিগেশন শুরু করুন
        </a>
    @else
        <div class="py-12 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl">
            <i class="fa-solid fa-location-slash text-2xl mb-2 text-slate-300 block"></i>
            নাগরিক জিপিএস লোকেশন শেয়ার করেননি।
        </div>
    @endif
</div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start gap-4 border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <span class="text-xs font-bold font-mono uppercase bg-green-50 text-green-800 px-2.5 py-1 rounded">
                            {{ $complaint->tracking_number }}
                        </span>
                        <h2 class="text-xl font-bold text-gray-800 mt-2">{{ $complaint->title }}</h2>
                    </div>
                </div>

                <div class="text-gray-700 leading-relaxed whitespace-pre-line text-sm mb-6">
                    <strong>অভিযোগের বিবরণ:</strong><br>
                    {{ $complaint->description }}
                </div>

                <div class="bg-gray-50 p-4 rounded-lg text-xs grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-600">
                    <div><strong>বিভাগ:</strong> {{ $complaint->division?->bn_name }}</div>
                    <div><strong>জেলা:</strong> {{ $complaint->district?->bn_name }}</div>
                    <div><strong>উপজেলা:</strong> {{ $complaint->upazila?->bn_name }}</div>
                    <div><strong>কোঅর্ডিনেট:</strong> {{ $complaint->latitude }}, {{ $complaint->longitude }}</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-gray-800 mb-4"><i class="fa-solid fa-paperclip text-green-700 mr-2"></i>সংযুক্ত প্রমণপত্র</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($complaint->attachments as $attachment)
                        <div class="border border-gray-100 rounded-lg overflow-hidden bg-gray-50 group relative shadow-sm">
                            @if($attachment->file_type === 'image')
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" class="w-full h-32 object-cover" alt="Evidence">
                            @else
                                <div class="w-full h-32 flex flex-col items-center justify-center bg-gray-900 text-white">
                                    <i class="fa-solid fa-video text-2xl text-red-500 mb-1"></i>
                                    <span class="text-[10px]">ভিডিও ফাইল</span>
                                </div>
                            @endif
                            <div class="p-2 text-center">
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline font-medium">
                                    ফাইলটি দেখুন <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-gray-800 mb-6"><i class="fa-solid fa-timeline text-green-700 mr-2"></i>অডিট ট্রেইল (তদন্তের ইতিহাস)</h3>
                <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
                    @foreach($complaint->auditLogs as $log)
                        <div class="mb-4 ml-6 relative">
                            <span class="absolute -left-[31px] top-0.5 bg-white border-2 border-green-600 rounded-full w-4 h-4 flex items-center justify-center"></span>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex flex-wrap justify-between items-center text-xs text-gray-400 mb-1 gap-2">
                                    <span>অ্যাকশন বাই: <strong class="text-gray-700">{{ $log->user?->phone ?? 'সিস্টেম ইঞ্জিন' }}</strong></span>
                                    <span>{{ $log->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <p class="text-sm font-medium text-gray-700 mt-1">
                                    স্ট্যাটাস পরিবর্তন: 
                                    <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-800 rounded font-mono font-bold uppercase">{{ $log->old_status }}</span> 
                                    ➔ 
                                    <span class="text-xs px-2 py-0.5 bg-green-100 text-green-800 rounded font-mono font-bold uppercase">{{ $log->new_status }}</span>
                                </p>
                                <p class="text-xs text-gray-600 mt-2 italic bg-white p-2 rounded border border-gray-50">
                                    " {{ $log->remarks }} "
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-6">
                <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">
                    <i class="fa-solid fa-gavel text-green-700 mr-2"></i>অফিশিয়াল অ্যাকশন প্যানেল
                </h3>
                
                <form action="{{ route('dashboard.status.update', $complaint->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">অভিযোগের বর্তমান অবস্থা নির্ধারণ করুন</label>
                        <select name="status" required class="...">
                            <option value="investigating" {{ $complaint->status == 'investigating' ? 'selected' : '' }}>🔵 তদন্তাধীন (Investigating)</option>
                            <option value="solved" {{ $complaint->status == 'solved' ? 'selected' : '' }}>🟢 সমাধানকৃত (Solved)</option>
                            <option value="rejected" {{ $complaint->status == 'rejected' ? 'selected' : '' }}>🔴 বাতিলকৃত (Rejected)</option>
                        </select>
                                                                
                </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">অফিশিয়াল মন্তব্য / রিমার্কস (বাধ্যতামূলক)</label>
                        <textarea name="remarks" rows="4" placeholder="তদন্তের অগ্রগতি বা ফাইলটি ক্লোজ করার কারণ বিস্তারিত লিখুন... (সর্বনিম্ন ১০ অক্ষর)" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-green-500 focus:outline-none @error('remarks') border-red-500 @enderror" required></textarea>
                        @error('remarks')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-medium text-sm py-2.5 px-4 rounded-lg shadow transition duration-150 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> স্ট্যাটাস আপডেট করুন
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcontent