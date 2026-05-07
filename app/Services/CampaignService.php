<?php

namespace App\Services;

use App\Models\Campaign;
use App\Jobs\SendCampaignEmail;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function createCampaign(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Lưu Campaign
            $campaign = Campaign::create([
                'title'      => $data['title'],
                'body'       => $data['body'],
                'send_at'    => $data['send_at'],
                'status'     => 'scheduled',
                'created_by' => auth()->id(),
            ]);

            // 2. Lưu quan hệ vào bảng trung gian và đẩy Job vào Queue
            if (!empty($data['subscriber_ids'])) {
                $campaign->subscribers()->attach($data['subscriber_ids']);

                // Lấy danh sách subscribers vừa lưu để duyệt
                $subscribers = $campaign->subscribers;

                foreach ($subscribers as $subscriber) {
                    // Đẩy tác vụ vào bảng 'jobs'
                    SendCampaignEmail::dispatch($campaign, $subscriber);
                }
            }

            return $campaign;
        });
    }
}
