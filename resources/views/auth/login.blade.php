<!DOCTYPE html>
<html lang="bn" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অফিশিয়াল লগইন - স্মার্ট নাগরিক প্ল্যাটফর্ম</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-green-800 text-white rounded-full flex items-center justify-center text-3xl shadow-md">
                <i class="fa-solid fa-building-shield"></i>
            </div>
            <h2 class="mt-4 text-2xl font-extrabold text-gray-900 font-sans">স্মার্ট নাগরিক প্ল্যাটফর্ম</h2>
            <p class="mt-1 text-xs text-green-700 font-medium bg-green-50 inline-block px-3 py-1 rounded-full border border-green-100">
                অফিশিয়াল প্রশাসনিক ড্যাশবোর্ড প্যানেল
            </p>
        </div>

        @if(session('success'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2.5 rounded-lg text-xs">
                <i class="fa-solid fa-circle-info mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <form class="mt-6 space-y-4" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-600 mb-1">অফিশিয়াল মোবাইল নম্বর</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required 
                        placeholder="যেমন: 017XXXXXXXX" 
                        class="pl-9 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-green-600 focus:outline-none @error('phone') border-red-500 @enderror">
                </div>
                @error('phone')
                    <p class="text-red-500 text-[11px] mt-1"><i class="fa-solid fa-triangle-exclamation mr-0.5"></i> {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-600 mb-1">গোপন পাসওয়ার্ড</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" name="password" type="password" required 
                        placeholder="••••••••" 
                        class="pl-9 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-green-600 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-green-700 focus:ring-green-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-gray-700 select-none">লগইন তথ্য মনে রাখুন</label>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-green-800 hover:bg-green-900 shadow-md transition duration-150 focus:outline-none">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3 text-green-600 group-hover:text-green-500">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </span>
                    ড্যাশবোর্ডে প্রবেশ করুন
                </button>
            </div>
        </form>

        <div class="text-center text-[10px] text-gray-400 pt-4 border-t border-gray-100">
            &copy; 2026 স্মার্ট সিটিজেন প্ল্যাটফর্ম। সর্বস্বত্ব সংরক্ষিত।
        </div>
    </div>
</body>
</html>