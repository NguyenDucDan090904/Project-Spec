<?php

namespace App\Services;

use App\Models\Campaign;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendCampaignEmail;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignService
{
    protected $campaignRepo;

    public function __construct(CampaignRepositoryInterface $campaignRepo)
    {
        $this->campaignRepo = $campaignRepo;
    }

    public function createCampaign(array $data)
    {
        return DB::transaction(function () use ($data) {
            $count = count($data['subscriber_ids'] ?? []);

            // 1. Lưu Campaign qua Repository
            $campaign = $this->campaignRepo->create([
                'title'            => $data['title'],
                'body'             => $data['body'],
                'send_at'          => $data['send_at'],
                'total_recipients' => $count,
                'sent_count'       => 0,
                'status'           => 'scheduled',
                'created_by'       => auth()->id(),
            ]);

            // 2. Lưu quan hệ và đẩy Job
            if ($count > 0) {
                $this->campaignRepo->attachSubscribers($campaign, $data['subscriber_ids']);

                // Gọi fresh data để tránh lỗi cache relation
                foreach ($campaign->subscribers()->get() as $subscriber) {
                    SendCampaignEmail::dispatch($campaign, $subscriber);
                }
            }

            return $campaign;
        });
    }

    public function retryFailedEmails(Campaign $campaign, $subscriberId = null)
    {
        return DB::transaction(function () use ($campaign, $subscriberId) {
            $failedSubscribers = $this->campaignRepo->getFailedSubscribers($campaign, $subscriberId);

            if ($failedSubscribers->isEmpty()) {
                return false;
            }

            foreach ($failedSubscribers as $subscriber) {
                // 1. Reset trạng thái trong bảng trung gian về 'sending' và xóa câu lỗi cũ
                $this->campaignRepo->updatePivotStatus($campaign, $subscriber->id, 'sending', null);

                // 2. Dispatch lại Job
                SendCampaignEmail::dispatch($campaign, $subscriber);
            }

            // 3. Cập nhật lại trạng thái Campaign tổng (nếu nó đang là completed/failed thì đổi lại thành sending)
            if (in_array($campaign->status, ['completed', 'failed'])) {
                $campaign->update(['status' => 'sending']);
            }

            return true;
        });
    }

    /**
     * Xử lý gửi chiến dịch (Đẩy vào hàng đợi)
     */
    public function sendCampaign(Campaign $campaign)
    {
        // 1. Cập nhật trạng thái chiến dịch sang 'processing' để tránh quét lặp
        $campaign->update(['status' => 'processing']);

        // 2. Lấy danh sách người nhận từ bảng trung gian
        // Giả sử bạn đã định nghĩa quan hệ 'subscribers' trong Model Campaign
        $subscribers = $campaign->subscribers;

        foreach ($subscribers as $subscriber) {
            // 3. Đẩy từng email vào hàng đợi (Queue)
            // Bạn hãy thay thế 'SendCampaignEmail' bằng tên Job thực tế bạn đã tạo
            dispatch(new \App\Jobs\SendCampaignEmail($campaign, $subscriber));
        }

        // 4. (Tùy chọn) Cập nhật trạng thái hoàn thành sau khi đã đẩy hết vào Queue
        // $campaign->update(['status' => 'sent']);
    }
}
