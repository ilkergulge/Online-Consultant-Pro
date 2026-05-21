<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consultant\StoreAvailabilityRequest;
use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $availabilities = $request->user()->availabilities;
        return view('consultant.availability.index', compact('availabilities'));
    }

    public function store(StoreAvailabilityRequest $request)
    {
        $user = $request->user();

        $user->availabilities()->create($request->validated());

        return redirect()->route('consultant.availability.index')->with('success', 'Availability added successfully.');
    }

    public function destroy(Availability $availability)
    {
        if ($availability->user_id === auth()->id()) {
            $availability->delete();
        }

        return redirect()->route('consultant.availability.index')->with('success', 'Availability removed successfully.');
    }
}
