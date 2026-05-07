<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateCampaignRequest;
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
        return redirect()->route('admin.dashboard')->with('success', 'Chiến dịch đã được lên lịch!');
    }
}
