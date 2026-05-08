<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSystemSettingRequest;
use App\Repositories\SystemSettingRepository;
use App\Services\SystemSettingService;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    protected $systemSettingService;
    protected $systemSettingRepository;

    public function __construct(SystemSettingService $systemSettingService, SystemSettingRepository $systemSettingRepository)
    {
        $this->systemSettingService = $systemSettingService;
        $this->systemSettingRepository = $systemSettingRepository;
    }

    public function index()
    {
        $settings = $this->systemSettingRepository->getAll()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function store(UpdateSystemSettingRequest $request)
    {
        $this->systemSettingService->handleUpdate($request->validated('settings'));
        return redirect()->route('admin.settings.index')->with('success', __('admin.settings_updated'));
    }
}
