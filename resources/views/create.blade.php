<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন অভিযোগ দাখিল করুন - স্মার্ট নাগরিক</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind_Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- ন্যাপবার -->
    <nav class="bg-white shadow-sm border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="h-10 w-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-left"></i>
                </div>
                <span class="font-bold text-sm text-slate-600">হোমপেজে ফিরে যান</span>
            </a>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Citizen Report Form</span>
        </div>
    </nav>

    <!-- ফরম সেকশন -->
    <main class="max-w-3xl mx-auto px-4 py-12">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 md:p-10">
            <div class="border-b border-slate-100 pb-6 mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                        <i class="fa-solid fa-file-pen text-emerald-600"></i> নতুন অভিযোগ দাখিল ফরম
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">সবগুলো তথ্য সঠিকভাবে পূরণ করে সাবমিট করুন। আপনার পরিচয় গোপন রাখা হবে।</p>
                </div>
                <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> সুরক্ষিত প্ল্যাটফর্ম
                </span>
            </div>

            <form action="{{ route('complaint.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6"">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2">আপনার মোবাইল নম্বর</label>
                        <input type="tel" id="citizen_phone" name="citizen_phone" required placeholder="যেমন: 017XXXXXXXX" 
                            pattern="^(01[3-9]\d{8})$" title="দয়া করে একটি সঠিক বাংলাদেশি মোবাইল নম্বর দিন"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition-all">
                        <p id="phone-feedback" class="text-[10px] text-red-500 mt-1 hidden">সঠিক মোবাইল নম্বর দিন (১১ ডিজিট)</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2">অভিযোগের প্রধান শিরোনাম / বিষয়</label>
                        <input type="text" name="title" required placeholder="সংক্ষেপে মূল সমস্যাটি লিখুন" 
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition-all">
                    </div>
                </div>

                <!-- এলাকা সিলেকশন ড্রপডাউনসমূহ -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2">বিভাগ</label>
                        <select id="division-select" name="division_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="">বিভাগ নির্বাচন করুন</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2">জেলা</label>
                        <select id="district-select" name="district_id" required disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl p-3.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="">প্রথমে বিভাগ সিলেক্ট করুন</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2">উপজেলা / থানা</label>
                        <select id="upazila-select" name="upazila_id" required disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl p-3.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                            <option value="">প্রথমে জেলা সিলেক্ট করুন</option>
                        </select>
                    </div>
                </div>
                <!-- জিপিএস লোকেশন বাটন -->
<div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl mb-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-location-crosshairs animate-pulse"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800">লাইভ জিপিএস লোকেশন</p>
                <p class="text-[10px] text-slate-500">আপনার বর্তমান লোকেশন স্বয়ংক্রিয়ভাবে যুক্ত করুন</p>
            </div>
        </div>
        <button type="button" id="get-location-btn" onclick="getLocation()" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-4 py-2.5 rounded-xl transition duration-300 flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-gps"></i> লোকেশন যুক্ত করুন
        </button>
    </div>
    
    <!-- লাইভ ফিডব্যাক মেসেজ -->
    <p id="location-status" class="text-[10px] font-bold text-emerald-600 mt-2 hidden">
        <i class="fa-solid fa-circle-check"></i> জিপিএস লোকেশন সফলভাবে লক করা হয়েছে!
    </p>
</div>

<!-- হিডেন ইনপুট ফিল্ড (যা ফর্মের সাথে কন্ট্রোলারে সাবমিট হবে) -->
<input type="hidden" id="latitude" name="latitude" value="">
<input type="hidden" id="longitude" name="longitude" value="">

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">অভিযোগের বিস্তারিত বিবরণ</label>
                    <textarea name="description" rows="5" required placeholder="ঘটনা বা সমস্যাটি বিস্তারিত এখানে বর্ণনা করুন..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:outline-none transition-all"></textarea>
                </div>

                <!-- ডিজিটাল প্রমাণ সংযুক্তি (ছবি অথবা ভিডিও) ও লাইভ প্রিভিউ -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">ডিজিটাল প্রমাণ সংযুক্তি (ছবি অথবা ভিডিও)</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 bg-slate-50 text-center hover:bg-slate-100/50 transition-all relative">
                        <input type="file" id="evidence-input" name="evidence" accept="image/*,video/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2 text-slate-500">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-emerald-600"></i>
                            <p class="text-xs font-bold">এখানে ক্লিক করে ফাইল সিলেক্ট করুন</p>
                            <p class="text-[10px] text-slate-400">সর্বোচ্চ ফাইল সাইজ: ৫০ মেগাবাইট</p>
                        </div>
                    </div>
                    <!-- ডাইনামিক ইমেজ/ভিডিও প্রিভিউ হোল্ডার -->
                    <div id="preview-container" class="mt-4 hidden p-4 border border-slate-100 rounded-2xl bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div id="file-thumbnail" class="w-16 h-16 rounded-xl object-cover overflow-hidden bg-slate-200 flex items-center justify-center"></div>
                            <div>
                                <p id="file-name" class="text-xs font-bold text-slate-800 truncate max-w-[200px]">Filename.jpg</p>
                                <p id="file-size" class="text-[10px] text-slate-400">0 KB</p>
                            </div>
                        </div>
                        <button type="button" id="remove-file-btn" class="text-slate-400 hover:text-red-500 p-2 text-sm transition"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm py-4 px-4 rounded-xl shadow-lg shadow-emerald-100 transition duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> অভিযোগ দাখিল করুন
                </button>
            </form>
        </div>
    </main>

    <script>
        const allDistricts = @json($districts);
        const allUpazilas = @json($upazilas);

        const divisionSelect = document.getElementById('division-select');
        const districtSelect = document.getElementById('district-select');
        const upazilaSelect = document.getElementById('upazila-select');

        // ১. বিভাগ সিলেক্ট করলে জেলা ফিল্টার হবে
        divisionSelect.addEventListener('change', function() {
            const divisionId = this.value;
            districtSelect.innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';
            districtSelect.disabled = true;
            districtSelect.classList.replace('bg-slate-50', 'bg-slate-100');
            upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';
            upazilaSelect.disabled = true;
            upazilaSelect.classList.replace('bg-slate-50', 'bg-slate-100');

            if (divisionId) {
                const filteredDistricts = allDistricts.filter(d => d.division_id == divisionId);
                filteredDistricts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name_bn || district.name;
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
                districtSelect.classList.replace('bg-slate-100', 'bg-slate-50');
            }
        });

        // ২. জেলা সিলেক্ট করলে উপজেলা ফিল্টার হবে
        districtSelect.addEventListener('change', function() {
            const districtId = this.value;
            upazilaSelect.innerHTML = '<option value="">উপজেলা / থানা নির্বাচন করুন</option>';
            upazilaSelect.disabled = true;
            upazilaSelect.classList.replace('bg-slate-50', 'bg-slate-100');

            if (districtId) {
                const filteredUpazilas = allUpazilas.filter(u => u.district_id == districtId);
                filteredUpazilas.forEach(upazila => {
                    const option = document.createElement('option');
                    option.value = upazila.id;
                    option.textContent = upazila.name_bn || upazila.name;
                    upazilaSelect.appendChild(option);
                });
                upazilaSelect.disabled = false;
                upazilaSelect.classList.replace('bg-slate-100', 'bg-slate-50');
            }
        });

        // ফাইল আপলোড লাইভ প্রিভিউ লজিক
        const fileInput = document.getElementById('evidence-input');
        const previewContainer = document.getElementById('preview-container');
        const fileThumbnail = document.getElementById('file-thumbnail');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeFileBtn = document.getElementById('remove-file-btn');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fileThumbnail.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    }
                    reader.readAsDataURL(file);
                } else if (file.type.startsWith('video/')) {
                    fileThumbnail.innerHTML = `<i class="fa-solid fa-video text-2xl text-emerald-600"></i>`;
                }
                previewContainer.classList.remove('hidden');
            }
        });

        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            previewContainer.classList.add('hidden');
            fileThumbnail.innerHTML = '';
        });

        // স্মার্ট মোবাইল নম্বর চেক
        const phoneInput = document.getElementById('citizen_phone');
        const phoneFeedback = document.getElementById('phone-feedback');
        phoneInput.addEventListener('input', function() {
            const pattern = /^(01[3-9]\d{8})$/;
            if(pattern.test(this.value) || this.value === "") {
                phoneFeedback.classList.add('hidden');
                this.classList.remove('border-red-500', 'focus:ring-red-500');
            } else {
                phoneFeedback.classList.remove('hidden');
                this.classList.add('border-red-500', 'focus:ring-red-500');
            }
        });

        function getLocation() {
    const statusText = document.getElementById('location-status');
    const btn = document.getElementById('get-location-btn');

    if (!navigator.geolocation) {
        alert("দুঃখিত! আপনার ব্রাউজারটি জিপিএস সমর্থন করে না।");
        return;
    }

    btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> লোকেশন খোঁজা হচ্ছে...';
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        // সফল হলে
        function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            
            btn.innerHTML = '<i class="fa-solid fa-check"></i> যুক্ত হয়েছে';
            btn.classList.replace('bg-emerald-600', 'bg-slate-500');
            statusText.classList.remove('hidden');
        },
        // ব্যর্থ হলে
        function(error) {
            btn.innerHTML = '<i class="fa-solid fa-gps"></i> আবার চেষ্টা করুন';
            btn.disabled = false;
            alert("লোকেশন পাওয়া যায়নি। অনুগ্রহ করে আপনার ব্রাউজার বা মোবাইলের লোকেশন সার্ভিস (GPS) চালু করুন।");
        }
    );
}
    </script>
</body>
</html>