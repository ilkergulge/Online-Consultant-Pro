<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consultant\StoreAvailabilityRequest;
use App\Repositories\AvailabilityRepository;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    protected $availabilityService;
    protected $availabilityRepository;

    public function __construct(AvailabilityService $availabilityService, AvailabilityRepository $availabilityRepository)
    {
        $this->availabilityService = $availabilityService;
        $this->availabilityRepository = $availabilityRepository;
    }

    public function index(Request $request)
    {
        $availabilities = $this->availabilityRepository->getAvailability($request->user());
        return view('consultant.availability.index', compact('availabilities'));
    }

    public function store(StoreAvailabilityRequest $request)
    {
        $this->availabilityService->handleCreate($request->user(), $request->validated());
        return redirect()->route('consultant.availability.index')->with('success', __('consultant.availability_created'));
    }

    public function destroy(Request $request, int $id)
    {
        $this->availabilityService->handleDelete($request->user(), $id);
        return redirect()->route('consultant.availability.index')->with('success', __('consultant.availability_deleted'));
    }
}
