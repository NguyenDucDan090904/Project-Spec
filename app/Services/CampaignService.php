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
            $count = count($data['subscriber_ids'] ?? []);
            // 1. Lưu Campaign
            $campaign = Campaign::create([
                'title'      => $data['title'],
                'body'       => $data['body'],
                'send_at'    => $data['send_at'],
                'total_recipients' => $count,
                'sent_count' => 0,
                'status'     => 'scheduled',
                'created_by' => auth()->id(),
            ]);

            // 2. Lưu quan hệ vào bảng trung gian và đẩy Job vào Queue
            if ($count > 0) {
                $campaign->subscribers()->attach($data['subscriber_ids']);

                foreach ($campaign->subscribers as $subscriber) {
                    SendCampaignEmail::dispatch($campaign, $subscriber);
                }
            }

            return $campaign;
        });
    }
}
