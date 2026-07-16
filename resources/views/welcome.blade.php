<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>স্মার্ট নাগরিক প্ল্যাটফর্ম - হোমপেজ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind_Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 bg-gradient-to-tr from-emerald-600 to-teal-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-emerald-200">
                    <i class="fa-solid fa-building-shield"></i>
                </div>
                <div>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900 block leading-tight">স্মার্ট নাগরিক</span>
                    <span class="text-[11px] text-emerald-600 font-bold tracking-wider uppercase">Citizen Portal</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(session()->has('user_area'))
                <button onclick="document.getElementById('area-modal').classList.remove('hidden')" class="text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-map-marker-alt text-emerald-600"></i> এলাকা পরিবর্তন
                </button>
                @endif
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-white hover:bg-emerald-600 border border-slate-200 hover:border-emerald-600 px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-lock-open text-xs"></i> অফিশিয়াল লগইন
                </a>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-br from-slate-900 via-emerald-950 to-teal-950 text-white py-20 px-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-4xl mx-auto text-center space-y-8 relative z-10">
            <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                সহজ সমাধান, ডিজিটাল নাগরিক সেবা
            </span>
            <h1 class="text-3xl md:text-5xl font-black leading-tight bg-gradient-to-r from-white via-slate-100 to-emerald-200 bg-clip-text text-transparent">
                আপনার এলাকার  সরাসরি কর্তৃপক্ষকে জানান
            </h1>
            <p class="text-slate-300 text-sm md:text-base max-w-xl mx-auto font-medium leading-relaxed">
                কোনো অ্যাপ ডাউনলোড করার ঝামেলা ছাড়াই সরাসরি আপনার অভিযোগ বা সমস্যা প্রশাসনের নিকট তুলে ধরুন এবং রিয়েল-টাইমে সেটির অগ্রগতি ট্র্যাক করুন।
            </p>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4 relative z-20">
                <a href="{{ route('complaint.create') }}" class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-extrabold text-sm px-8 py-4 rounded-2xl shadow-xl shadow-emerald-950/40 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-plus text-base"></i> একটি নতুন অভিযোগ জানান
                </a>
                
                @if(session()->has('user_area'))
                <button onclick="document.getElementById('area-modal').classList.remove('hidden')" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white font-bold text-sm px-8 py-4 rounded-2xl border border-white/10 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-location-dot"></i> এলাকা পরিবর্তন করুন
                </button>
                @endif
            </div>

            <div class="bg-white p-2.5 rounded-2xl shadow-2xl max-w-xl mx-auto text-slate-900 mt-12 border border-white/20">
                <form action="{{ route('complaint.track') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $complaint->tracking_number ?? '') }}" required
                            placeholder="আপনার অভিযোগের ট্র্যাকিং নম্বরটি দিন..." 
                            class="w-full pl-11 pr-3 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition-all">
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-3.5 rounded-xl transition duration-300 shadow-md whitespace-nowrap flex items-center justify-center gap-2">
                        <i class="fa-solid fa-compass"></i> অবস্থা ট্র্যাক করুন
                    </button>
                </form>
            </div>

            @if(session('error'))
                <p class="text-red-300 text-xs mt-3 bg-red-950/50 inline-block px-4 py-2 rounded-xl border border-red-900/30">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ session('error') }}
                </p>
            @endif

            @if(session('success_complaint'))
                <div class="bg-emerald-50 border-2 border-emerald-500 text-emerald-950 p-4 rounded-xl text-sm font-bold max-w-xl mx-auto shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i> {{ session('success_complaint') }}
                </div>
            @endif
        </div>
    </header>

    <div id="area-modal" class="{{ session()->has('user_area') ? 'hidden' : '' }} fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all duration-300">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 md:p-8 shadow-2xl border border-slate-100">
            <div class="text-center mb-6 relative">
                @if(session()->has('user_area'))
                <button onclick="document.getElementById('area-modal').classList.add('hidden')" class="absolute -top-2 right-0 text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                @endif
                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center mx-auto mb-3 text-lg">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">আপনার এলাকা নির্বাচন করুন</h3>
                <p class="text-xs text-slate-500 mt-1">আপনার এলাকার নির্দিষ্ট অভিযোগ পরিস্থিতি দেখতে এলাকাটি সিলেক্ট করুন।</p>
            </div>
            
            <form action="{{ route('area.save') }}" method="POST" id="area-select-form" class="space-y-4">
                @csrf
                <div>
                    <div class="mb-4">
                        <button type="button" id="btn-gps" class="w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl py-3 text-xs font-bold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-location-crosshairs text-sm animate-pulse"></i> 
                            জিপিএস দিয়ে স্বয়ংক্রিয়ভাবে এলাকা সিলেক্ট করুন
                        </button>
                        <p id="gps-status" class="text-[10px] text-slate-500 mt-1 text-center hidden"></p>
                    </div>

                    <label class="block text-xs font-bold text-slate-600 mb-1.5">বিভাগ</label>
                    <select id="division" name="division_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <option value="">বিভাগ নির্বাচন করুন</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name_bn ?? $division->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">জেলা</label>
                    <select id="district" name="district_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <option value="">প্রথমে বিভাগ সিলেক্ট করুন</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">উপজেলা/থানা</label>
                    <select id="upazila" name="upazila_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <option value="">প্রথমে জেলা সিলেক্ট করুন</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl py-3.5 text-xs font-bold shadow-lg shadow-emerald-200 transition duration-300 flex items-center justify-center gap-2 mt-2">
                    পোর্টালে প্রবেশ করুন <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    @if(session()->has('user_area') && $complaints->count() > 0)
    <section class="max-w-4xl mx-auto px-4 py-16">
        <div class="space-y-8">
            <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-rose-500 text-lg animate-bounce"></i> 
                    আপনার এলাকার চলমান অভিযোগ ও নাগরিকদের মতামত
                </h3>
                <button onclick="document.getElementById('area-modal').classList.remove('hidden')" class="text-xs text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-4 py-2 rounded-xl font-bold transition">
                    এলাকা পরিবর্তন
                </button>
            </div>
            
            <!-- ফেসবুক স্টাইল পোস্ট ফিড -->
            <div class="space-y-6">
                @foreach($complaints as $complaint)
                    <div class="bg-white rounded-3xl shadow-md border border-slate-100 overflow-hidden transition-all hover:shadow-lg">
                        
                        <!-- পোস্ট হেডার (ইউজার আইডি ও স্ট্যাটাস) -->
                        <div class="p-6 flex items-center justify-between border-b border-slate-50 bg-slate-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-tr from-slate-200 to-slate-300 rounded-full flex items-center justify-center text-slate-600 font-bold text-sm">
                                    <i class="fa-solid fa-user-shield"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-400 block">অভিযোগ আইডি: #{{ $complaint->tracking_number }}</span>
                                    <span class="text-[11px] text-slate-500 font-medium">{{ $complaint->created_at ? $complaint->created_at->diffForHumans() : 'কিছুক্ষণ আগে' }}</span>
                                </div>
                            </div>
                            
                            <div>
                                @if($complaint->status == 'pending')
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold px-3 py-1.5 rounded-full">🟡 অপেক্ষমান</span>
                                @elseif($complaint->status == 'investigating')
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-3 py-1.5 rounded-full">🔵 তদন্তাধীন</span>
                                @elseif($complaint->status == 'rejected')
                                    <span class="bg-red-50 text-red-700 border border-red-200 text-xs font-bold px-3 py-1.5 rounded-full">🔴 বাতিলকৃত</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-full">🟢 সমাধানকৃত</span>
                                @endif
                            </div>
                        </div>

                        <!-- পোস্ট কন্টেন্ট (শিরোনাম ও বিবরণ) -->
                        <div class="p-6 space-y-3">
                            <h4 class="text-lg font-bold text-slate-900 leading-snug">{{ $complaint->title }}</h4>
                            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $complaint->description }}</p>
                            
                            <!-- সঠিক অবস্থান / ম্যাপ বাটন -->
                            <div class="pt-2">
                                @if($complaint->latitude && $complaint->longitude)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $complaint->latitude }},{{ $complaint->longitude }}" target="_blank" class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold px-4 py-2 rounded-xl transition-all border border-rose-100">
                                        <i class="fa-solid fa-map-location-dot text-sm text-rose-600"></i> গুগল ম্যাপে সঠিক অবস্থান দেখুন
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-2 bg-slate-50 text-slate-500 text-xs font-semibold px-4 py-2 rounded-xl border border-slate-100">
                                        <i class="fa-solid fa-map-pin"></i> নির্দিষ্ট লোকেশন সংযুক্ত নেই (ম্যানুয়াল ঠিকানা)
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(isset($complaint->attachments) && $complaint->attachments->count() > 0)
    <div class="border-t border-b border-slate-100 bg-black max-h-[450px] flex flex-col items-center justify-center overflow-hidden w-full">
        @foreach($complaint->attachments as $attach)
            
            @if($attach->file_type === 'video')
                <video controls class="w-full aspect-video object-contain bg-slate-900 max-h-[450px]">
                    <source src="{{ asset('storage/' . $attach->file_path) }}" type="video/mp4">
                    আপনার ব্রাউজারটি ভিডিও সাপোর্ট করছে না।
                </video>
            @else
                <img src="{{ asset('storage/' . $attach->file_path) }}" 
                     alt="Complaint Media" 
                     class="w-full aspect-video object-cover max-h-[450px]"
                     onerror="this.onerror=null; this.src='{{ asset('images/default-placeholder.png') }}';">
            @endif

        @endforeach
    </div>
@endif

                        <!-- ইন্টারেক্টিভ কমেন্ট এবং মতামত সেকশন -->
                        <div class="bg-slate-50/70 p-6 border-t border-slate-100 space-y-4">
                            <h5 class="text-xs font-bold text-slate-700 flex items-center gap-1.5 uppercase tracking-wider">
                                <i class="fa-regular fa-comments text-emerald-600 text-sm"></i> নাগরিকদের মতামত ও আলোচনা
                            </h5>

                            <!-- আগের করা কমেন্টগুলোর তালিকা -->
                            <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                @if(isset($complaint->comments) && $complaint->comments->count() > 0)
                                    @foreach($complaint->comments as $comment)
                                        <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-sm text-xs">
                                            <div class="flex items-center justify-between mb-1">
                                                <!-- 🟢 এখানে 'নাগরিক মতামত' এর বদলে ডাইনামিক নাম বসানো হলো -->
                                                <span class="font-bold text-slate-800">
                                                    <i class="fa-regular fa-user text-slate-400 mr-1"></i>
                                                    {{ $comment->user_name ?? 'অজ্ঞাত নাগরিক' }}
                                                </span>
                                                <span class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-600 font-medium leading-relaxed">{{ $comment->comment_text }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-[11px] text-slate-400 italic text-center py-2">এখনো কোনো মতামত দেওয়া হয়নি। প্রথম মন্তব্যটি আপনার হোক!</p>
                                @endif
</div>

                            <!-- নতুন কমেন্ট করার ফর্ম -->
                            <!-- নিশ্চিত করুন এখানে আপনি $complaint->id অথবা $complaint->tracking_number পাঠাচ্ছেন -->
                            <form action="{{ route('complaint.comment', $complaint->id) }}" method="POST" class="mt-2 flex gap-2">
                                @csrf
                                <input type="text" name="comment_text" required placeholder="এই সমস্যা বা এলাকার পরিস্থিতি নিয়ে আপনার মতামত লিখুন..." 
                                    class="flex-grow bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-3 rounded-xl transition duration-300 shadow-sm flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-paper-plane"></i> পোস্ট
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <footer class="bg-white border-t border-slate-100 py-8 text-center text-xs text-slate-400 font-medium">
        &copy; 2026 স্মার্ট নাগরিক প্ল্যাটফর্ম। সর্বস্বত্ব সংরক্ষিত।
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const divisionSelect = document.getElementById('modal-division');
        const districtSelect = document.getElementById('modal-district');
        const upazilaSelect = document.getElementById('modal-upazila');
        const gpsBtn = document.getElementById('btn-gps');
        const gpsStatus = document.getElementById('gps-status');

        // ১. বিভাগ অনুযায়ী জেলা লোড করা
        divisionSelect?.addEventListener('change', function () {
            const divisionId = this.value;
            districtSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';
            upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';

            if (!divisionId) {
                districtSelect.innerHTML = '<option value="">প্রথমে বিভাগ সিলেক্ট করুন</option>';
                return;
            }

            fetch(`/get-districts/${divisionId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.id}">${district.name_bn || district.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    districtSelect.innerHTML = '<option value="">ত্রুটি ঘটেছে, আবার চেষ্টা করুন</option>';
                });
        });

        // ২. জেলা অনুযায়ী উপজেলা লোড করা
        districtSelect?.addEventListener('change', function () {
            const districtId = this.value;
            upazilaSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';

            if (!districtId) {
                upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';
                return;
            }

            fetch(`/get-upazilas/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    upazilaSelect.innerHTML = '<option value="">উপজেলা নির্বাচন করুন</option>';
                    data.forEach(upazila => {
                        upazilaSelect.innerHTML += `<option value="${upazila.id}">${upazila.name_bn || upazila.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    upazilaSelect.innerHTML = '<option value="">ত্রুটি ঘটেছে, আবার চেষ্টা করুন</option>';
                });
        });

        // ৩. জিপিএস ট্র্যাকিং
        gpsBtn?.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert("দুঃখিত, আপনার ব্রাউজারে জিপিএস সুবিধা নেই।");
                return;
            }

            if (gpsStatus) {
                gpsStatus.classList.remove('hidden');
                gpsStatus.textContent = "আপনার অবস্থান খোঁজা হচ্ছে... অনুগ্রহ করে অনুমতি (Allow) দিন।";
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    if (gpsStatus) gpsStatus.textContent = "ঠিকানা শনাক্ত করা হচ্ছে...";

                    fetch(`/detect-location?lat=${lat}&lon=${lon}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (gpsStatus) {
                                    gpsStatus.textContent = `সফল হয়েছে! (${data.upazila_name}, ${data.district_name})`;
                                }
                                
                                if (divisionSelect) {
                                    divisionSelect.value = data.division_id;
                                }
                                
                                loadDistrictsAndSelect(data.division_id, data.district_id, data.upazila_id);
                            } else {
                                if (gpsStatus) {
                                    gpsStatus.textContent = "দুঃখিত, আপনার এলাকা ডাটাবেজে মেলানো যায়নি। ম্যানুয়ালি সিলেক্ট করুন।";
                                }
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            if (gpsStatus) gpsStatus.textContent = "সার্ভারে ত্রুটি ঘটেছে।";
                        });
                },
                function(error) {
                    if (gpsStatus) {
                        gpsStatus.textContent = "লোকেশন পারমিশন না দিলে স্বয়ংক্রিয় সিলেক্ট করা সম্ভব নয়।";
                    }
                }
            );
        });

        // ৪. অটো-সিলেকশন ফাংশন
        function loadDistrictsAndSelect(divisionId, districtId, upazilaId) {
            if (!districtSelect || !upazilaSelect) return;

            fetch(`/get-districts/${divisionId}`)
                .then(res => res.json())
                .then(districts => {
                    districtSelect.innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';
                    districts.forEach(d => {
                        const selected = d.id == districtId ? 'selected' : '';
                        districtSelect.innerHTML += `<option value="${d.id}" ${selected}>${d.name_bn || d.name}</option>`;
                    });

                    if (districtId) {
                        fetch(`/get-upazilas/${districtId}`)
                            .then(res => res.json())
                            .then(upazilas => {
                                upazilaSelect.innerHTML = '<option value="">উপজেলা নির্বাচন করুন</option>';
                                upazilas.forEach(u => {
                                    const selected = u.id == upazilaId ? 'selected' : '';
                                    upazilaSelect.innerHTML += `<option value="${u.id}" ${selected}>${u.name_bn || u.name}</option>`;
                                });
                            });
                    } else {
                        upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';
                    }
                });
        }
    });
    </script>
</body>
</html>