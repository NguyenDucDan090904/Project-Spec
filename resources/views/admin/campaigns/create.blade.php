<x-app-layout>
    <!-- PHẦN NÀY ĐỂ FIX LỖI $header ---->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tạo Chiến Dịch Mới') }}
        </h2>
    </x-slot>

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
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nội dung</label>
                            <textarea name="body" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                            @error('body') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Thời gian gửi</label>
                            <input type="datetime-local" name="send_at" value="{{ old('send_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('send_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- AJAX Search Subscribers -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700">Tìm & Chọn người nhận</label>
                            <input type="text" id="search-input" placeholder="Nhập tên hoặc email..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            <div id="search-results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 hidden shadow-lg max-h-48 overflow-y-auto">
                                <!-- Kết quả AJAX -->
                            </div>
                        </div>

                        <!-- Danh sách badge đã chọn -->
                        <div id="selected-list" class="flex flex-wrap gap-2 mt-2"></div>
                        @error('subscriber_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div class="mt-4">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Lưu Chiến Dịch
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
</x-app-layout>
