<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আপনার এলাকা নির্বাচন করুন - স্মার্ট সিটিজেন</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-xl border border-slate-100 mx-4">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800">আপনার এলাকা নির্বাচন করুন</h2>
            <p class="text-xs text-slate-500 mt-1">আপনার চারপাশের অভিযোগ এবং সমাধান দেখতে এলাকা সিলেক্ট করুন</p>
        </div>

        <form action="{{ route('area.save') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">বিভাগ</label>
                <select name="division_id" id="division" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">বিভাগ সিলেক্ট করুন</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->name_bn ?? $division->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">জেলা</label>
                <select name="district_id" id="district" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">প্রথমে বিভাগ সিলেক্ট করুন</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">উপজেলা/থানা</label>
                <select name="upazila_id" id="upazila" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">প্রথমে জেলা সিলেক্ট করুন</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">ইউনিয়ন (যদি থাকে)</label>
                <select name="union_id" id="union" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">প্রথমে উপজেলা সিলেক্ট করুন</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-emerald-100 flex items-center justify-center gap-2">
                পোর্টালে প্রবেশ করুন <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const divisionSelect = document.getElementById('division');
            const districtSelect = document.getElementById('district');
            const upazilaSelect = document.getElementById('upazila');
            const unionSelect = document.getElementById('union');

            // ১. বিভাগ সিলেক্ট করলে জেলা লোড হবে
            divisionSelect.addEventListener('change', function () {
                const divisionId = this.value;
                
                // জেলা, উপজেলা ও ইউনিয়ন রিসেট করা
                districtSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';
                upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';
                unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা সিলেক্ট করুন</option>';

                if (!divisionId) {
                    districtSelect.innerHTML = '<option value="">প্রথমে বিভাগ সিলেক্ট করুন</option>';
                    return;
                }

                fetch(`/get-districts/${divisionId}`)
                    .then(response => response.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">জেলা সিলেক্ট করুন</option>';
                        data.forEach(district => {
                            districtSelect.innerHTML += `<option value="${district.id}">${district.name_bn || district.name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching districts:', error);
                        districtSelect.innerHTML = '<option value="">ত্রুটি ঘটেছে, আবার চেষ্টা করুন</option>';
                    });
            });

            // ২. জেলা সিলেক্ট করলে উপজেলা লোড হবে
            districtSelect.addEventListener('change', function () {
                const districtId = this.value;
                
                upazilaSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';
                unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা সিলেক্ট করুন</option>';

                if (!districtId) {
                    upazilaSelect.innerHTML = '<option value="">প্রথমে জেলা সিলেক্ট করুন</option>';
                    return;
                }

                fetch(`/get-upazilas/${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        upazilaSelect.innerHTML = '<option value="">উপজেলা সিলেক্ট করুন</option>';
                        data.forEach(upazila => {
                            upazilaSelect.innerHTML += `<option value="${upazila.id}">${upazila.name_bn || upazila.name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching upazilas:', error);
                        upazilaSelect.innerHTML = '<option value="">ত্রুটি ঘটেছে, আবার চেষ্টা করুন</option>';
                    });
            });

            // ৩. উপজেলা সিলেক্ট করলে ইউনিয়ন লোড হবে
            upazilaSelect.addEventListener('change', function () {
                const upazilaId = this.value;
                
                if(!unionSelect) return; // যদি ফর্মে ইউনিয়ন ফিল্ড না থাকে

                unionSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';

                if (!upazilaId) {
                    unionSelect.innerHTML = '<option value="">প্রথমে উপজেলা সিলেক্ট করুন</option>';
                    return;
                }

                fetch(`/get-unions/${upazilaId}`)
                    .then(response => response.json())
                    .then(data => {
                        unionSelect.innerHTML = '<option value="">ইউনিয়ন সিলেক্ট করুন (ঐচ্ছিক)</option>';
                        if(data.length === 0) {
                            unionSelect.innerHTML = '<option value="">কোনো ইউনিয়ন পাওয়া যায়নি</option>';
                        }
                        data.forEach(union => {
                            unionSelect.innerHTML += `<option value="${union.id}">${union.name_bn || union.name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching unions:', error);
                        unionSelect.innerHTML = '<option value="">ত্রুটি ঘটেছে, আবার চেষ্টা করুন</option>';
                    });
            });
        });
    </script>
</body>
</html>