@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-slate-50/50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-2.5 h-8 bg-emerald-600 rounded-full inline-block"></span>
                    অভিযোগ নিয়ন্ত্রণ কেন্দ্র
                </h1>
                <p class="text-slate-500 text-sm mt-1">রিয়েল-টাইম ডাটাবেজ আপডেট এবং আধুনিক অভিযোগ ট্র্যাকিং ড্যাশবোর্ড।</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 animate-pulse">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                    সিস্টেম লাইভ
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="relative overflow-hidden group bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-2xl shadow-xl shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start text-white">
                    <div>
                        <p class="text-sm font-medium text-blue-100 uppercase tracking-wider">মোট প্রাপ্ত অভিযোগ</p>
                        <h3 class="text-4xl font-extrabold mt-2 font-mono tracking-tight">{{ $total_complaints }}</h3>
                    </div>
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                        <i class="fa-solid fa-folder-open text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-blue-100">
                    <span>সর্বমোট ডেটাবেজ রেকর্ড</span>
                    <i class="fa-solid fa-circle-info"></i>
                </div>
            </div>

            <div class="relative overflow-hidden group bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-2xl shadow-xl shadow-orange-500/10 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start text-white">
                    <div>
                        <p class="text-sm font-medium text-amber-500-100 uppercase tracking-wider">অপেক্ষমান অভিযোগ (Pending)</p>
                        <h3 class="text-4xl font-extrabold mt-2 font-mono tracking-tight">{{ $pending_complaints }}</h3>
                    </div>
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                        <i class="fa-solid fa-clock text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-amber-100">
                    <span>তাত্ক্ষণিক ব্যবস্থা প্রয়োজন</span>
                    <i class="fa-solid fa-triangle-exclamation animate-bounce"></i>
                </div>
            </div>

            <div class="relative overflow-hidden group bg-gradient-to-br from-emerald-600 to-teal-700 p-6 rounded-2xl shadow-xl shadow-emerald-500/10 transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex justify-between items-start text-white">
                    <div>
                        <p class="text-sm font-medium text-emerald-100 uppercase tracking-wider">সফলভাবে সমাধানকৃত</p>
                        <h3 class="text-4xl font-extrabold mt-2 font-mono tracking-tight">{{ $resolved_complaints }}</h3>
                    </div>
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                        <i class="fa-solid fa-circle-check text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-emerald-100">
                    <span>নাগরিক সন্তুষ্টির হার ১০%</span>
                    <i class="fa-solid fa-square-poll-vertical"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">আগত অভিযোগসমূহের লাইভ তালিকা</h3>
                    <p class="text-xs text-slate-400 mt-0.5">অভিযোগের গুরুত্ব নির্ধারণ করে দ্রুত ব্যবস্থা নিন।</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full border border-indigo-100">
                        মোট: {{ $complaints->total() }}টি অভিযোগ
                    </span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4 text-center">ট্র্যাকিং নম্বর</th>
                            <th class="p-4">অভিযোগের বিবরণ ও ছবি</th>
                            <th class="p-4">তারিখ</th>
                            <th class="p-4 text-center">এসকেলেশন</th>
                            <th class="p-4 text-center">স্ট্যাটাস</th>
                            <th class="p-4 text-center">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 text-sm divide-y divide-slate-100">
                        @forelse($complaints as $complaint)
                            <tr class="hover:bg-slate-50/40 transition duration-200 group">
                                <td class="p-4 text-center">
                                    <span class="inline-block px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-mono font-bold text-xs border border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-700 group-hover:border-indigo-200 transition">
                                        {{ $complaint->tracking_number }}
                                    </span>
                                </td>
                                
                                <td class="p-4">
                                   
                                    <div class="flex items-start gap-4">
                                        @if($complaint->attachments && $complaint->attachments->isNotEmpty())
                                        @php 
                                            $firstAttach = $complaint->attachments->first(); 
                                            // পাথ ক্লিন করা: ডেটাবেজে যদি ভুল করে 'public/' বা 'storage/' লেখাটি থাকে, তা বাদ দিয়ে ফ্রেশ পাথ নেওয়া হলো
                                            $cleanPath = str_replace(['public/', 'storage/'], '', $firstAttach->file_path);
                                            $fileUrl = asset('storage/' . $cleanPath);
                                        @endphp

                                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-900 flex-shrink-0 flex items-center justify-center border border-slate-200 shadow-sm relative group-hover:scale-105 transition-transform">
                                            @if($firstAttach->file_type === 'video')
                                                <div class="text-white text-xs flex flex-col items-center justify-center">
                                                    <i class="fa-solid fa-video text-base mb-0.5 text-blue-400"></i>
                                                    <span class="text-[9px] font-bold uppercase">ভিডিও</span>
                                                </div>
                                            @else
                                                <img src="{{ $fileUrl }}" 
                                                    alt="Attachment" 
                                                    class="w-full h-full object-cover"
                                                    onerror="this.onerror=null; this.src='https://placehold.co/150x150?text=No+Image';">
                                            @endif
                                        </div>
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-slate-50 flex-shrink-0 flex items-center justify-center border border-dashed border-slate-200 text-slate-400">
                                            <i class="fa-regular fa-image text-lg"></i>
                                        </div>
                                    @endif

                                        <div>
                                            <h4 class="font-bold text-slate-800 text-base leading-snug group-hover:text-indigo-600 transition">
                                                {{ Str::limit($complaint->title, 55) }}
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-400 mt-1.5">
                                                <span class="flex items-center gap-1 font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                                    <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                                                    {{ $complaint->upazila?->bn_name ?? $complaint->upazila?->name_bn ?? 'ইউপি পাওয়া যায়নি' }}, 
                                                    {{ $complaint->district?->bn_name ?? $complaint->district?->name_bn ?? 'জেলা পাওয়া যায়নি' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="p-4 text-slate-500 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-700">{{ $complaint->created_at->format('d M, Y') }}</span>
                                        <span class="text-xs text-slate-400 mt-0.5">{{ $complaint->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $complaint->escalation_level > 2 ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $complaint->escalation_level > 2 ? 'bg-rose-500' : 'bg-slate-400' }}"></span>
                                        লেভেল {{ $complaint->escalation_level }}
                                    </span>
                                </td>
                                
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($complaint->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            অপেক্ষমান
                                        </span>
                                    @elseif($complaint->status === 'investigating')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></span>
                                            তদন্তাধীন
                                        </span>
                                    @elseif($complaint->status === 'solved' || $complaint->status === 'resolved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            সমাধানকৃত
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            বাতিলকৃত
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="p-4 text-center whitespace-nowrap">
                                    <a href="{{ route('dashboard.show', $complaint->id) }}" 
                                       class="inline-flex items-center justify-center gap-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-semibold py-2 px-4 rounded-xl shadow-md shadow-emerald-500/10 hover:shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-0.5 transition duration-150">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        বিস্তারিত দেখুন
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-16 text-center">
                                    <div class="max-w-sm mx-auto">
                                        <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mx-auto mb-4">
                                            <i class="fa-regular fa-folder-open text-2xl"></i>
                                        </div>
                                        <h3 class="text-slate-800 font-bold text-lg">কোনো অভিযোগ খুঁজে পাওয়া যায়নি!</h3>
                                        <p class="text-slate-400 text-xs mt-1">সব অভিযোগ সফলভাবে হ্যান্ডেল করা হয়েছে অথবা কোনো ডেটা ইনপুট করা নেই।</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex justify-center items-center">
                {{ $complaints->links() }}
            </div>
        </div>
    </div>
</div>
@endsection