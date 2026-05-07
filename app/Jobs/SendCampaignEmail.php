<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\Middleware\RateLimited;

class SendCampaignEmail implements ShouldQueue
{
    public $tries = 5;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;
    protected $subscriber;

    public function __construct(Campaign $campaign, Subscriber $subscriber)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
    }

    public function handle()
    {
        // Thực hiện gửi mail thực tế
        Mail::to($this->subscriber->email)->send(new CampaignMail($this->campaign));
        // Cập nhật trạng thái THÀNH CÔNG trong bảng trung gian
        DB::table('campaign_recipients')
            ->where('campaign_id', $this->campaign->id)
            ->where('subscriber_id', $this->subscriber->id)
            ->update(['status' => 'sent']);

        // Tăng biến đếm trong bảng campaigns
        $this->campaign->increment('sent_count');

        // Kiểm tra nếu đã gửi xong tất cả thì đổi trạng thái Campaign
        if ($this->campaign->sent_count >= $this->campaign->total_recipients) {
            $this->campaign->update(['status' => 'completed']);
        }
    }

    public function middleware()
    {
        return [new RateLimited('mailtrap-limit')];
    }

    public function failed($exception)
    {
        // Cập nhật trạng thái THẤT BẠI vào bảng trung gian
        DB::table('campaign_recipients')
            ->where('campaign_id', $this->campaign->id)
            ->where('subscriber_id', $this->subscriber->id)
            ->update([
                'status' => 'failed',
                'error_message' => substr($exception->getMessage(), 0, 255)
            ]);
    }
}
