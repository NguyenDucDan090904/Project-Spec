<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateCampaignRequest;
use App\Models\Campaign;
use App\Services\CampaignService;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignController extends Controller
{
    protected $campaignService;
    protected $campaignRepo;

    public function __construct(
        CampaignService $campaignService,
        CampaignRepositoryInterface $campaignRepo
    ) {
        $this->campaignService = $campaignService;
        $this->campaignRepo = $campaignRepo;
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(CreateCampaignRequest $request)
    {
        $this->campaignService->createCampaign($request->validated());
        return redirect()->route('admin.campaigns.index')->with('success', 'Chiến dịch đã được lên lịch!');
    }

    public function index()
    {
        // Lấy danh sách qua Repo
        $campaigns = $this->campaignRepo->getPaginated(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        // Lấy danh sách người nhận qua Repo
        $recipients = $this->campaignRepo->getRecipientsPaginated($campaign, 10);

        // Tính phần trăm trên Controller (hoặc có thể đưa vào Helper/Model Attribute)
        $progress = $campaign->total_recipients > 0
            ? round(($campaign->sent_count / $campaign->total_recipients) * 100)
            : 0;

        return view('admin.campaigns.show', compact('campaign', 'recipients', 'progress'));
    }

    public function retryAll(Campaign $campaign)
    {
        $this->campaignService->retryFailedEmails($campaign);
        return back()->with('success', 'Hệ thống đang tiến hành gửi lại toàn bộ email bị lỗi.');
    }

    public function retrySingle(Campaign $campaign, $subscriberId)
    {
        $this->campaignService->retryFailedEmails($campaign, $subscriberId);
        return back()->with('success', 'Đang gửi lại email cho người nhận này.');
    }
}
