<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Repositories\FrontendConsultantRepository;
use App\Services\SlotCalculationService;
use Illuminate\Http\Request;

class ConsultantListController extends Controller
{
    protected $consultantRepository;
    protected $slotService;

    public function __construct(FrontendConsultantRepository $consultantRepository, SlotCalculationService $slotService)
    {
        $this->consultantRepository = $consultantRepository;
        $this->slotService = $slotService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'min_price', 'max_price', 'search']);
        $consultants = $this->consultantRepository->getFilteredConsultants($filters);
        $categories = Category::all();

        return view('frontend.consultants.index', compact('consultants', 'categories'));
    }

    public function show(User $consultant, Request $request)
    {
        if ($consultant->role !== 'consultant') {
            abort(404);
        }

        $consultant->load(['consultantProfile', 'availabilities']);

        $date = $request->get('date', now()->toDateString());
        $availableSlots = $this->slotService->calculateSlots($consultant, $date);

        return view('frontend.consultants.show', compact('consultant', 'availableSlots', 'date'));
    }
}
