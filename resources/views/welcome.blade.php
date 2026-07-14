<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>স্মার্ট নাগরিক প্ল্যাটফর্ম - আপনার অভিযোগ আমাদের দায়িত্ব</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- ন্যাপবার -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 bg-green-800 text-white rounded-full flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-building-shield"></i>
                </div>
                <div>
                    <span class="font-black text-lg tracking-tight text-gray-950 block leading-none">স্মার্ট নাগরিক</span>
                    <span class="text-[10px] text-green-700 font-semibold tracking-wider uppercase">Citizen Portal</span>
                </div>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-xs font-bold text-gray-600 hover:text-green-800 border border-gray-200 px-4 py-2 rounded-xl transition duration-150">
                    <i class="fa-solid fa-lock-open mr-1"></i> অফিশিয়াল লগইন
                </a>
            </div>
        </div>
    </nav>

    <!-- হিরো সেকশন -->
    <header class="bg-gradient-to-br from-green-900 to-emerald-950 text-white py-12 px-4">
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <h1 class="text-2xl md:text-4xl font-extrabold leading-tight">সরাসরি ওয়েবসাইট থেকে অভিযোগ জানান বা ট্র্যাক করুন</h1>
            <p class="text-green-100 text-xs md:text-sm max-w-xl mx-auto opacity-90">
                অ্যাপ ছাড়াই এখন আপনার এলাকার যেকোনো সমস্যা সরাসরি উপযুক্ত কর্তৃপক্ষের কাছে জমা দিন।
            </p>

            <!-- ট্র্যাকিং ফর্ম -->
            <div class="bg-white p-2 rounded-2xl shadow-xl max-w-xl mx-auto text-gray-900 mt-6">
                <form action="{{ route('complaint.track') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $complaint->tracking_number ?? '') }}" required
                            placeholder="অভিযোগের ট্র্যাকিং নম্বরটি দিন (যেমন: COMP-123456)" 
                            class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                    <button type="submit" class="bg-green-800 hover:bg-green-900 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow-md whitespace-nowrap">
                        অবস্থা ট্র্যাক করুন
                    </button>
                </form>
            </div>

            @if(session('error'))
                <p class="text-red-300 text-xs mt-2 bg-red-950/40 inline-block px-4 py-1 rounded-lg border border-red-900/30">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ session('error') }}
                </p>
            @endif

            @if(session('success_complaint'))
                <div class="bg-yellow-50 border-2 border-yellow-400 text-gray-900 p-4 rounded-xl text-sm font-bold max-w-xl mx-auto shadow-lg">
                    <i class="fa-solid fa-circle-check text-green-700 text-lg mr-1"></i> {{ session('success_complaint') }}
                </div>
            @endif
        </div>
    </header>

    <!-- ট্র্যাকিং রেজাল্ট -->
    @if(isset($complaint))
        <section class="max-w-4xl mx-auto px-4 py-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden p-6">
                <h3 class="font-bold text-gray-800 text-sm mb-2">অভিযোগের শিরোনাম: {{ $complaint->title }}</h3>
                <p class="text-xs text-gray-500">বর্তমান অবস্থা: <span class="font-bold text-green-700 uppercase">{{ $complaint->status }}</span></p>
            </div>
        </section>
    @endif

    <!-- মেইন ফর্ম সেকশন -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-green-800"></i> নতুন অভিযোগ দাখিল ফরম
                </h2>
                <p class="text-xs text-gray-400 mt-1">সবগুলো তথ্য সঠিকভাবে পূরণ করে সাবমিট করুন। আপনার পরিচয় গোপন রাখা হবে।</p>
            </div>

            <form action="{{ route('complaint.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">আপনার মোবাইল নম্বর</label>
                        <input type="text" name="citizen_phone" required placeholder="যেমন: 017XXXXXXXX" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">অভিযোগের প্রধান শিরোনাম / বিষয়</label>
                        <input type="text" name="title" required placeholder="সংক্ষেপে মূল সমস্যাটি লিখুন" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>
                </div>

                <!-- এলাকা সিলেকশন ড্রপডাউনসমূহ -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">বিভাগ</label>
                        <select id="division-select" name="division_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="">বিভাগ নির্বাচন করুন</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">জেলা</label>
                        <select id="district-select" name="district_id" required disabled class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="">প্রথমে বিভাগ সিলেক্ট করুন</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">উপজেলা / থানা</label>
                        <select id="upazila-select" name="upazila_id" required disabled class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="">প্রথমে জেলা সিলেক্ট করুন</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">অভিযোগের বিস্তারিত বিবরণ</label>
                    <textarea name="description" rows="5" required placeholder="ঘটনা বা সমস্যাটি বিস্তারিত এখানে বর্ণনা করুন..." 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">ডিজিটাল প্রমাণ সংযুক্তি (ছবি অথবা ভিডিও)</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 bg-gray-50 text-center hover:bg-gray-100/50 transition relative">
                        <input type="file" name="evidence" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-1 text-gray-500">
                            <i class="fa-solid fa-cloud-arrow-up text-xl text-green-700"></i>
                            <p class="text-xs font-medium">এখানে ক্লিক করে ছবি অথবা ভিডিও ফাইল সিলেক্ট করুন</p>
                            <p class="text-[10px] text-gray-400">সর্বোচ্চ ফাইল সাইজ: ৫০ মেগাবাইট</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md transition duration-150 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> অভিযোগ দাখিল করুন
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-400">
        &copy; 2026 স্মার্ট নাগরিক প্ল্যাটফর্ম। সর্বস্বত্ব সংরক্ষিত।
    </footer>

    <!-- ড্রপডাউন ফিল্টারিং এর মূল জাভাস্ক্রিপ্ট ম্যাজিক -->
    <script>
        // কন্ট্রোলার থেকে আসা পুরো ডাটা জাভাস্ক্রিপ্ট ভেরিয়েবলে কনভার্ট করে নিলাম
        const allDistricts = @json($districts);
        const allUpazilas = @json($upazilas);

        const divisionSelect = document.getElementById('division-select');
        const districtSelect = document.getElementById('district-select');
        const upazilaSelect = document.getElementById('upazila-select');

        // ১. বিভাগ সিলেক্ট করলে জেলা ফিল্টার হবে
        divisionSelect.addEventListener('change', function() {
            const divisionId = this.value;
            
            // জেলা ও থানা রিসেট এবং ডিজেবল করা
            districtSelect.innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';
            districtSelect.disabled = true;
            districtSelect.classList.replace('bg-gray-50', 'bg-gray-100');
            
            upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';
            upazilaSelect.disabled = true;
            upazilaSelect.classList.replace('bg-gray-50', 'bg-gray-100');

            if (divisionId) {
                // শুধু ওই বিভাগের জেলাগুলো বের করা
                const filteredDistricts = allDistricts.filter(d => d.division_id == divisionId);
                
                filteredDistricts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name_bn || district.name;
                    districtSelect.appendChild(option);
                });

                districtSelect.disabled = false;
                districtSelect.classList.replace('bg-gray-100', 'bg-gray-50');
            }
        });

        // ২. জেলা সিলেক্ট করলে উপজেলা/থানা ফিল্টার হবে
        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            
            upazilaSelect.innerHTML = '<option value="">উপজেলা / থানা নির্বাচন করুন</option>';
            upazilaSelect.disabled = true;
            upazilaSelect.classList.replace('bg-gray-50', 'bg-gray-100');

            if (districtId) {
                // শুধু ওই জেলার উপজেলাগুলো বের করা
                const filteredUpazilas = allUpazilas.filter(u => u.district_id == districtId);
                
                filteredUpazilas.forEach(upazila => {
                    const option = document.createElement('option');
                    option.value = upazila.id;
                    option.textContent = upazila.name_bn || upazila.name;
                    upazilaSelect.appendChild(option);
                });

                upazilaSelect.disabled = false;
                upazilaSelect.classList.replace('bg-gray-100', 'bg-gray-50');
            }
        });
    </script>
</body>
</html>