<?php

namespace App\Services;

use App\Repositories\SystemSettingRepository;

class SystemSettingService
{
    protected $systemSettingRepository;

    public function __construct(SystemSettingRepository $systemSettingRepository)
    {
        $this->systemSettingRepository = $systemSettingRepository;
    }

    public function handleUpdate(array $settings)
    {
        $this->systemSettingRepository->updateMany($settings);
    }
}
