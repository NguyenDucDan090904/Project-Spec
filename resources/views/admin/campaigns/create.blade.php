@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tạo Chiến Dịch Mới') }}
    </h2>
@endsection

@section('content')
    <!-- Toàn bộ phần này sẽ đổ vào biến slot trong layout -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600 font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.campaigns.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Tiêu đề chiến dịch</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   placeholder="VD: Bản tin tháng 5/2026"
                                   class="block w-full p-3.5 rounded-lg border-2 border-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 transition duration-150">
                            @error('title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Nội dung Email</label>
                            <textarea name="body" rows="5"
                                      placeholder="Nhập nội dung thông điệp tại đây..."
                                      class="block w-full p-4 rounded-lg border-2 border-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 transition duration-150">{{ old('body') }}</textarea>
                            @error('body') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Thời gian gửi</label>
                                <input type="datetime-local" name="send_at" value="{{ old('send_at') }}"
                                       class="block w-full p-3.5 rounded-lg border-2 border-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 transition duration-150">
                                @error('send_at') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Tìm & Chọn người nhận</label>
                            <div class="relative">
                                <input type="text" id="search-input" placeholder="Gõ để tìm tên hoặc email..."
                                       class="block w-full p-3.5 pl-10 rounded-lg border-2 border-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>

                            <div id="search-results" class="absolute z-20 w-full bg-white border-2 border-indigo-200 rounded-lg mt-1 hidden shadow-xl max-h-60 overflow-y-auto">
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Đã chọn:</p>
                            <div id="selected-list" class="flex flex-wrap gap-2 min-h-[40px] p-3 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            </div>
                            @error('subscriber_ids') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="scheduled_at">Lên lịch gửi (Bỏ trống nếu muốn gửi ngay):</label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control"
                                   value="{{ old('scheduled_at') }}">
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-indigo-200 transition-all transform active:scale-95">
                                Lưu & Lên lịch gửi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chèn jQuery trực tiếp ở đây để đảm bảo nó chạy sau khi DOM load -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedSubscribers = new Set();

            $('#search-input').on('keyup', function() {
                let query = $(this).val();
                if (query.length < 2) {
                    $('#search-results').addClass('hidden');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.subscribers.search') }}",
                    data: { q: query },
                    success: function(data) {
                        let html = '';
                        data.forEach(sub => {
                            html += `<div class="p-2 hover:bg-indigo-100 cursor-pointer border-b last:border-0"
                                          onclick="addSubscriber(${sub.id}, '${sub.name}')">
                                        ${sub.name} (${sub.email})
                                     </div>`;
                        });
                        $('#search-results').html(html).removeClass('hidden');
                    }
                });
            });

            window.addSubscriber = function(id, name) {
                if (!selectedSubscribers.has(id)) {
                    selectedSubscribers.add(id);
                    $('#selected-list').append(`
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            ${name}
                            <input type="hidden" name="subscriber_ids[]" value="${id}">
                            <button type="button" onclick="removeSubscriber(this, ${id})" class="ml-2 text-indigo-400 hover:text-indigo-600">×</button>
                        </span>
                    `);
                }
                $('#search-results').addClass('hidden');
                $('#search-input').val('');
            };

            window.removeSubscriber = function(btn, id) {
                selectedSubscribers.delete(id);
                $(btn).parent().remove();
            };
        });
    </script>
@endsection
