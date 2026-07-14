@extends('layouts.admin')

@content('content')
<div class="container mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">মোট অভিযোগ প্রাপ্তি</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $total_complaints }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                <i class="fa-solid fa-folder-open"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">অপেক্ষমান অভিযোগ (Pending)</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pending_complaints }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">সফলভাবে সমাধানকৃত</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $resolved_complaints }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">আগত অভিযোগসমূহের তালিকা</h3>
            <span class="text-xs bg-gray-100 text-gray-600 font-medium px-2.5 py-1 rounded-full">লাইভ আপডেট</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-sm font-semibold border-b border-gray-100">
                        <th class="p-4 text-center">ট্র্যাকিং নম্বর</th>
                        <th class="p-4">অভিযোগের শিরোনাম</th>
                        <th class="p-4">তারিখ</th>
                        <th class="p-4 text-center">এসকেলেশন লেভেল</th>
                        <th class="p-4 text-center">স্ট্যাটাস</th>
                        <th class="p-4 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm divide-y divide-gray-50">
                    @forelse($complaints as $complaint)
                        <tr class="hover:bg-gray-50/70 transition duration-150">
                            <td class="p-4 text-center font-mono font-bold text-green-700">
                                {{ $complaint->tracking_number }}
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-gray-800">{{ Str::limit($complaint->title, 50) }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-red-400 mr-1"></i> 
                                    {{ $complaint->upazila?->name_bn }}, {{ $complaint->district?->name_bn }}
                                </p>
                            </td>
                            <td class="p-4 text-gray-500">
                                {{ $complaint->created_at->format('d M, Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2 py-1 text-xs rounded font-bold {{ $complaint->escalation_level > 2 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                    Level  {{ $complaint->escalation_level }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                @if($complaint->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">অপেক্ষমান</span>
                                @elseif($complaint->status === 'investigating')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">তদন্তাধীন</span>
                                @elseif($complaint->status === 'resolved')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">সমাধানকৃত</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">বাতিল</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('dashboard.show', $complaint->id) }}" class="inline-flex items-center gap-1 bg-green-800 hover:bg-green-900 text-white text-xs font-medium py-1.5 px-3 rounded transition">
                                    <i class="fa-solid fa-eye"></i> বিস্তারিত
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                কোনো অভিযোগ খুঁজে পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $complaints->links() }}
        </div>
    </div>
</div>
@endcontent