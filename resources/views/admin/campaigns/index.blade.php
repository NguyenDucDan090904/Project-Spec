@extends('layouts.app')

@section('content')
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 shadow-sm">Bảng Điều Khiển</h1>
                    <p class="text-slate-500 font-medium mt-1">Quản lý hiệu suất và các đợt phát hành Email</p>
                </div>
                <a href="{{ route('admin.campaigns.create') }}"
                   class="group relative inline-flex items-center px-8 py-3 bg-slate-900 text-white font-bold rounded-2xl overflow-hidden transition-all hover:pr-12 active:scale-95 shadow-lg shadow-slate-300">
                    <span class="relative z-10 text-base">Tạo Chiến Dịch</span>
                    <svg class="absolute right-4 w-5 h-5 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-slate-200 overflow-hidden">
                <table class="min-w-full border-collapse">
                    <thead class="bg-slate-900">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Thông tin chiến dịch</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Lịch trình</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Hiệu suất gửi</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Trạng thái</th>
                        <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Quản lý</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($campaigns as $campaign)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-2xl border-2 border-indigo-100 overflow-hidden group-hover:scale-110 transition-transform shadow-sm">
                                        <img src="{{ asset('images/money.jpg') }}"
                                             alt="Title"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="ml-5">
                                        <div class="text-base font-black text-slate-800">{{ $campaign->title }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-6">
                                <div class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-lg text-slate-700 text-sm font-bold border border-slate-200">
                                    <svg class="w-4 h-4 mr-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($campaign->send_at)->format('d.m.y') }}
                                </div>
                            </td>

                            <td class="px-6 py-6">
                                @php
                                    $total = (int) $campaign->total_recipients;
                                    $sent = (int) $campaign->sent_count;
                                    $percent = $total > 0 ? min(100, round(($sent / $total) * 100)) : 0;
                                @endphp
                                <div class="w-full max-w-[180px]">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-lg font-black text-slate-800 leading-none">{{ $percent }}%</span>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $sent }} / {{ $total }}</span>
                                    </div>
                                    <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.4)] transition-all duration-1000"
                                             style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-6 text-center">
                                @php
                                    $statusMap = [
                                        'completed' => ['bg' => 'bg-green-500', 'text' => 'text-white', 'label' => 'XONG'],
                                        'sending'   => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'label' => 'ĐANG GỬI'],
                                        'failed'    => ['bg' => 'bg-red-500', 'text' => 'text-white', 'label' => 'LỖI'],
                                        'scheduled' => ['bg' => 'bg-indigo-500', 'text' => 'text-white', 'label' => 'LỊCH'],
                                        'draft'     => ['bg' => 'bg-slate-400', 'text' => 'text-white', 'label' => 'NHÁP'],
                                    ];
                                    $st = $statusMap[$campaign->status] ?? $statusMap['draft'];
                                @endphp
                                <span class="{{ $st['bg'] }} {{ $st['text'] }} px-4 py-1.5 rounded-full text-[10px] font-black tracking-widest shadow-md">
                                {{ $st['label'] }}
                            </span>
                            </td>

                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.campaigns.show', $campaign->id) }}"
                                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border-2 border-slate-200 text-slate-600 hover:border-indigo-600 hover:text-indigo-600 hover:rotate-12 transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-10">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
@endsection
