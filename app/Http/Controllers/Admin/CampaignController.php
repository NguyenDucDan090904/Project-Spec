<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateCampaignRequest;
use App\Models\Campaign;
use App\Services\CampaignService;
use App\Models\Subscriber;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
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
        // Lấy danh sách campaign, sắp xếp cái mới nhất lên đầu
        $campaigns = Campaign::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        // Lấy danh sách người nhận kèm trạng thái từ bảng trung gian
        $recipients = $campaign->subscribers()
            ->withPivot('status', 'error_message')
            ->paginate(10);

        // Tính toán phần trăm hoàn thành
        $progress = $campaign->total_recipients > 0
            ? round(($campaign->sent_count / $campaign->total_recipients) * 100)
            : 0;

        return view('admin.campaigns.show', compact('campaign', 'recipients', 'progress'));
    }
}
