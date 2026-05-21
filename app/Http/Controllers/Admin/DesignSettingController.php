<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class DesignSettingController extends Controller
{
    public function edit()
    {
        $primaryColor = SystemSetting::where('key', 'primary_color')->value('value') ?? '#4f46e5';
        $siteLogo = SystemSetting::where('key', 'site_logo')->value('value') ?? '';
        return view('admin.settings.design', compact('primaryColor', 'siteLogo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        SystemSetting::updateOrCreate(
            ['key' => 'primary_color'],
            ['value' => $request->primary_color, 'group' => 'design']
        );

        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('logos', 'public');
            SystemSetting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $path, 'group' => 'design']
            );
        }

        return redirect()->back()->with('success', 'Design settings updated successfully.');
    }
}
