<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consultant\UpdateProfileRequest;
use App\Models\Category;
use App\Repositories\ConsultantProfileRepository;
use App\Services\ConsultantProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileService;
    protected $profileRepository;

    public function __construct(ConsultantProfileService $profileService, ConsultantProfileRepository $profileRepository)
    {
        $this->profileService = $profileService;
        $this->profileRepository = $profileRepository;
    }

    public function edit(Request $request)
    {
        $profile = $this->profileRepository->getProfile($request->user());
        $categories = Category::all();
        return view('consultant.profile.edit', compact('profile', 'categories'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->handleUpdate($request->user(), $request->validated());
        return redirect()->route('consultant.profile.edit')->with('success', __('consultant.profile_updated'));
    }
}
