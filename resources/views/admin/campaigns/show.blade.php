@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.campaigns.index') }}" class="p-2 bg-white border-2 border-slate-200 rounded-xl hover:border-indigo-600 transition-colors">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-black text-2xl text-slate-800 tracking-tight">Chi Tiết Báo Cáo</h2>
        </div>

        <form action="{{ route('admin.campaigns.retry_all', $campaign->id) }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-rose-600 text-white rounded-xl text-sm font-black shadow-lg shadow-rose-200 hover:bg-rose-700 active:scale-95 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                GỬI LẠI TẤT CẢ LỖI
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl mb-8 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-6 md:mb-0">
                        <span class="text-indigo-400 text-xs font-black uppercase tracking-[0.3em]">Đang xem chiến dịch</span>
                        <h1 class="text-4xl font-black mt-2">{{ $campaign->title }}</h1>
                        <p class="text-slate-400 mt-2 font-medium">Lịch gửi: {{ \Carbon\Carbon::parse($campaign->send_at)->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="relative flex items-center justify-center">
                        <svg class="w-32 h-32 transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="10" fill="transparent" class="text-slate-800" />
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="10" fill="transparent"
                                    class="text-indigo-500 transition-all duration-1000 shadow-glow"
                                    stroke-dasharray="351.8"
                                    stroke-dashoffset="{{ 351.8 - (351.8 * $progress) / 100 }}" />
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-2xl font-black text-white">{{ $progress }}%</span>
                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-widest">Done</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-black text-slate-800 uppercase tracking-widest text-sm">Danh sách vận hành</h3>
                    <div class="flex space-x-6">
                        <div class="text-sm font-bold"><span class="text-slate-400">TỔNG:</span> {{ $campaign->total_recipients }}</div>
                        <div class="text-sm font-bold"><span class="text-green-500">XONG:</span> {{ $campaign->sent_count }}</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                        <tr class="text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] bg-white">
                            <th class="px-8 py-6">Người nhận</th>
                            <th class="px-8 py-6 text-center">Trạng thái</th>
                            <th class="px-8 py-6 text-right">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                        @foreach($recipients as $subscriber)
                            <tr class="group hover:bg-slate-50/80 transition-all">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-700">{{ $subscriber->email }}</div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($subscriber->pivot->status == 'sent')
                                        <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black shadow-sm">THÀNH CÔNG</span>
                                    @elseif($subscriber->pivot->status == 'failed')
                                        <span class="px-4 py-1.5 bg-rose-100 text-rose-700 rounded-full text-[10px] font-black shadow-sm">THẤT BẠI</span>
                                    @else
                                        <span class="px-4 py-1.5 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black shadow-sm tracking-widest">ĐANG GỬI</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    @if($subscriber->pivot->status == 'failed')
                                        <form action="{{ route('admin.campaigns.retry_single', ['campaign' => $campaign->id, 'subscriber' => $subscriber->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-lg hover:bg-indigo-600 transition-all active:scale-90 shadow-md">
                                                GỬI LẠI NGAY
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-300 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-slate-50/50">
                    {{ $recipients->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module">
        // Lắng nghe kênh 'campaign.{id}'
        Echo.channel('campaign.{{ $campaign->id }}')
            .listen('CampaignProgressUpdated', (e) => {
                console.log('Nhận dữ liệu mới:', e);

                // 1. Cập nhật thanh tiến độ (Thanh ngang)
                const progressBar = document.getElementById('progress-bar');
                if(progressBar) {
                    progressBar.style.width = e.progress + '%';
                }

                // 2. Cập nhật con số % ở vòng tròn hoặc văn bản
                const progressText = document.getElementById('progress-text');
                if(progressText) {
                    progressText.innerText = e.progress + '%';
                }

                // 3. Cập nhật con số sent_count / total_recipients
                const sentCountText = document.getElementById('sent-count-text');
                if(sentCountText) {
                    sentCountText.innerText = e.campaign.sent_count;
                }

                // 4. Nếu muốn xịn hơn, bạn có thể reload nhẹ danh sách hoặc đổi màu status
            });
    </script>
@endsection
