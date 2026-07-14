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

    <!-- ন্যাপবার -->
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
            <div>
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-white hover:bg-emerald-600 border border-slate-200 hover:border-emerald-600 px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-lock-open text-xs"></i> অফিশিয়াল লগইন
                </a>
            </div>
        </div>
    </nav>

    <!-- হিরো সেকশন -->
    <header class="bg-gradient-to-br from-slate-900 via-emerald-950 to-teal-950 text-white py-20 px-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-4xl mx-auto text-center space-y-8 relative z-10">
            <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                সহজ সমাধান, ডিজিটাল নাগরিক সেবা
            </span>
            <h1 class="text-3xl md:text-5xl font-black leading-tight bg-gradient-to-r from-white via-slate-100 to-emerald-200 bg-clip-text text-transparent">
                আপনার এলাকার সমস্যা সরাসরি কর্তৃপক্ষকে জানান
            </h1>
            <p class="text-slate-300 text-sm md:text-base max-w-xl mx-auto font-medium leading-relaxed">
                কোনো অ্যাপ ডাউনলোড করার ঝামেলা ছাড়াই সরাসরি আপনার অভিযোগ বা সমস্যা প্রশাসনের নিকট তুলে ধরুন এবং রিয়েল-টাইমে সেটির অগ্রগতি ট্র্যাক করুন।
            </p>

            <!-- সরাসরি অভিযোগ করার স্মার্ট বাটন (CTA) -->
            <!-- সরাসরি অভিযোগ করার স্মার্ট বাটন (CTA) -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4 relative z-20">
                <a href="{{ route('complaint.create') }}" class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-extrabold text-sm px-8 py-4 rounded-2xl shadow-xl shadow-emerald-950/40 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-plus text-base"></i> একটি নতুন অভিযোগ জানান
                </a>
                
                @if(session('user_district_id'))
                <button onclick="document.getElementById('area-modal').classList.remove('hidden')" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white font-bold text-sm px-8 py-4 rounded-2xl border border-white/10 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-location-dot"></i> এলাকা পরিবর্তন করুন
                </button>
                @endif
            </div>

            <!-- ট্র্যাকিং ফর্ম -->
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

    <!-- জেলা/উপজেলা সিলেকশন পপআপ মোডাল -->
    <div id="area-modal" class="{{ session('user_district_id') ? 'hidden' : '' }} fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-all duration-300">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 md:p-8 shadow-2xl border border-slate-100">
            <div class="text-center mb-6 relative">
                @if(session('user_district_id'))
                <button onclick="document.getElementById('area-modal').classList.add('hidden')" class="absolute -top-2 right-0 text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                @endif
                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center mx-auto mb-3 text-lg">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">আপনার এলাকা নির্বাচন করুন</h3>
                <p class="text-xs text-slate-500 mt-1">আপনার এলাকার নির্দিষ্ট অভিযোগ পরিস্থিতি দেখতে প্রথমে জেলা ও উপজেলা সিলেক্ট করুন।</p>
            </div>
            
            <form id="area-select-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">জেলা</label>
                    <select id="modal-district" name="district_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <option value="">জেলা নির্বাচন করুন</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->bn_name ?? $district->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">উপজেলা/থানা</label>
                    <select id="modal-upazila" name="upazila_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                        <option value="">প্রথমে জেলা সিলেক্ট করুন</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl py-3.5 text-xs font-bold shadow-lg shadow-emerald-200 transition duration-300 flex items-center justify-center gap-2 mt-2">
                    ড্যাশবোর্ডে প্রবেশ করুন
                </button>
            </form>
        </div>
    </div>

    <!-- এলাকার চলমান অভিযোগসমূহ সেকশন -->
    @if(session('user_district_id') && $complaints->count() > 0)
    <section class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-slate-100">
            <h3 class="text-md font-bold text-slate-800 mb-6 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-rose-500"></i> আপনার এলাকার চলমান অভিযোগসমূহ</span>
                <button onclick="document.getElementById('area-modal').classList.remove('hidden')" class="text-xs text-emerald-600 hover:underline font-bold">এলাকা পরিবর্তন</button>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($complaints as $complaint)
                    <div class="border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-md transition-all p-5 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-bold text-slate-400">ID: {{ $complaint->tracking_number }}</span>
                                @if($complaint->status == 'investigating')
                                    <span class="bg-amber-50 text-amber-700 border border-amber-100 text-[10px] font-bold px-2.5 py-1 rounded-full">🟡 অপেক্ষমান</span>
                                @elseif($complaint->status == 'rejected')
                                    <span class="bg-red-50 text-red-700 border border-red-100 text-[10px] font-bold px-2.5 py-1 rounded-full">🔴 বাতিলকৃত</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-2.5 py-1 rounded-full">🟢 সমাধানকৃত</span>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 line-clamp-1 mb-1">{{ $complaint->title }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">{{ $complaint->description }}</p>
                        </div>
                        @if($complaint->attachments->count() > 0)
                            <div class="mt-4 flex gap-2">
                                @foreach($complaint->attachments as $attach)
                                    <img src="{{ asset('storage/' . $attach->file_path) }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @elseif(session('user_district_id'))
    <section class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl text-center text-xs text-slate-400 py-10 shadow-sm border border-slate-100">
            🎉 আপনার এলাকায় এই মুহূর্তে কোনো পেন্ডিং অভিযোগ নেই!
        </div>
    </section>
    @endif

    <footer class="bg-white border-t border-slate-100 py-8 text-center text-xs text-slate-400 font-medium">
        &copy; 2026 স্মার্ট নাগরিক প্ল্যাটফর্ম। সর্বস্বত্ব সংরক্ষিত।
    </footer>

    <script>
        // মোডালের ভেতর জেলা বদলালে উপজেলা ফিল্টার করা
        document.getElementById('modal-district')?.addEventListener('change', function() {
            const districtId = this.value;
            const upazilaSelect = document.getElementById('modal-upazila');
            upazilaSelect.innerHTML = '<option value="">উপজেলা নির্বাচন করুন</option>';
            const upazilas = @json($upazilas);
            const filtered = upazilas.filter(u => u.district_id == districtId);
            filtered.forEach(upazila => {
                const option = document.createElement('option');
                option.value = upazila.id;
                option.textContent = upazila.bn_name || upazila.name;
                upazilaSelect.appendChild(option);
            });
        });

        // এলাকা ফর্ম সাবমিট করা
        document.getElementById('area-select-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('{{ route("select.area") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => { if(data.success) { location.reload(); } });
        });
    </script>
</body>
</html>