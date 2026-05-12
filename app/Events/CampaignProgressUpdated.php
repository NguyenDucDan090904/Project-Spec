<?php

namespace App\Events;

use App\Models\Campaign;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignProgressUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $campaignId;
    public $progress;
    public $sentCount;

    public function __construct(Campaign $campaign)
    {
        $this->campaignId = $campaign->id;
        $this->sentCount = $campaign->sent_count;
        $this->progress = $campaign->total_recipients > 0
            ? round(($campaign->sent_count / $campaign->total_recipients) * 100)
            : 0;
    }

    public function broadcastOn()
    {
        // Kênh công khai để dễ test trước
        return new Channel('campaign-progress.' . $this->campaignId);
    }

    public function broadcastAs()
    {
        return 'progress.updated';
    }
}
