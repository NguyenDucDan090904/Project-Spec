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
    }

    public function middleware()
    {
        return [new RateLimited('mailtrap-limit')];
    }
}
