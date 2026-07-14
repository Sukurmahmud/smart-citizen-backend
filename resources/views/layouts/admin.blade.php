<!DOCTYPE html>
<html lang="bn" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>স্মার্ট নাগরিক প্ল্যাটফর্ম - ড্যাশবোর্ড</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full">
    <div class="min-full flex">
        <div class="w-64 bg-green-900 text-white flex flex-col justify-between hidden md:flex">
            <div class="p-5">
                <h1 class="text-xl font-bold tracking-wider mb-10"><i class="fa-solid fa-shield-halved mr-2"></i>স্মার্ট সিটিজেন</h1>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard.index') }}" class="block py-2.5 px-4 rounded transition duration-200 bg-green-700 text-white">
                        <i class="fa-solid fa-chart-line mr-2"></i> ড্যাশবোর্ড
                    </a>
                </nav>
            </div>
            <div class="p-5 border-t border-green-800">
                <p class="text-sm">লগইন আছেন: <br><span class="font-semibold text-yellow-400">{{ Auth::user()->phone }}</span></p>
            </div>
            <div class="p-5 border-t border-green-800">
                <p class="text-sm mb-3">লগইন আছেন: <br><span class="font-semibold text-yellow-400">{{ Auth::user()->phone }}</span></p>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 px-3 bg-red-800 hover:bg-red-900 text-white text-xs font-semibold rounded transition flex items-center gap-2">
                        <i class="fa-solid fa-power-off text-red-300"></i> প্যানেল থেকে লগআউট
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center bg-white py-4 px-6 border-b border-gray-200">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</h2>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-4">রোল: <strong class="uppercase text-green-700">{{ Auth::user()->role }}</strong></span>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>