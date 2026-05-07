<?php

namespace App\Services;

use App\Models\Campaign;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function createCampaign(array $data)
    {
        // Dùng Transaction để đảm bảo nếu lưu bảng trung gian lỗi thì bảng Campaign cũng sẽ Rollback
        return DB::transaction(function () use ($data) {
            // 1. Lưu vào bảng campaigns
            $campaign = Campaign::create([
                'title'      => $data['title'],
                'body'       => $data['body'],
                'send_at'    => $data['send_at'],
                'status'     => 'scheduled',
                'created_by' => auth()->id(),
            ]);

            // 2. Lưu toàn bộ người nhận được chọn vào bảng trung gian
            if (!empty($data['subscriber_ids'])) {
                // Laravel sẽ tự động lặp qua mảng subscriber_ids và insert vào bảng trung gian
                $campaign->subscribers()->attach($data['subscriber_ids']);
            }

            return $campaign;
        });
    }
}
