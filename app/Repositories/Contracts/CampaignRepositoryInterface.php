<?php

namespace App\Repositories\Contracts;

use App\Models\Campaign;

interface CampaignRepositoryInterface
{
    public function getPaginated($perPage = 10);
    public function create(array $data);
    public function attachSubscribers(Campaign $campaign, array $subscriberIds);
    public function getRecipientsPaginated(Campaign $campaign, $perPage = 10);
    public function getFailedSubscribers(Campaign $campaign, $subscriberId = null);
    public function updatePivotStatus(Campaign $campaign, $subscriberId, $status, $errorMessage = null);
}
