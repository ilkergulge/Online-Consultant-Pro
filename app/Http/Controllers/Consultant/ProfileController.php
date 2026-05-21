<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consultant\UpdateProfileRequest;
use App\Services\ConsultantProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ConsultantProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit(Request $request)
    {
        $profile = $this->profileService->getProfileForUser($request->user());
        return view('consultant.profile.edit', compact('profile'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return redirect()->route('consultant.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
