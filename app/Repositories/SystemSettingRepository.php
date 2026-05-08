<?php

namespace App\Repositories;

use App\Models\SystemSetting;

class SystemSettingRepository
{
    public function getAll()
    {
        return SystemSetting::all();
    }

    public function updateMany(array $settings)
    {
        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
