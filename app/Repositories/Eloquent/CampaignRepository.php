<?php

namespace App\Repositories\Eloquent;

use App\Models\Campaign;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function getPaginated($perPage = 10)
    {
        return Campaign::latest('created_at')->paginate($perPage);
    }

    public function create(array $data)
    {
        return Campaign::create($data);
    }

    public function attachSubscribers(Campaign $campaign, array $subscriberIds)
    {
        $campaign->subscribers()->attach($subscriberIds);
    }

    public function getRecipientsPaginated(Campaign $campaign, $perPage = 10)
    {
        return $campaign->subscribers()
            ->withPivot('status', 'error_message')
            ->paginate($perPage);
    }

    public function getFailedSubscribers(Campaign $campaign, $subscriberId = null)
    {
        $query = $campaign->subscribers()->wherePivot('status', 'failed');

        // Nếu truyền ID cụ thể, chỉ lấy người đó. Nếu không, lấy tất cả lỗi.
        if ($subscriberId) {
            $query->where('subscriber_id', $subscriberId);
        }

        return $query->get();
    }

    public function updatePivotStatus(Campaign $campaign, $subscriberId, $status, $errorMessage = null)
    {
        $campaign->subscribers()->updateExistingPivot($subscriberId, [
            'status' => $status,
            'error_message' => $errorMessage
        ]);
    }
}
