<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class SlotCalculationService
{
    public function calculateSlots(User $consultant, string $dateString)
    {
        $date = Carbon::parse($dateString);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

        $availabilities = $consultant->availabilities()->where('day_of_week', $dayOfWeek)->get();

        if ($availabilities->isEmpty()) {
            return [];
        }

        $slotDuration = $consultant->consultantProfile->slot_duration ?? 60;
        $slots = [];

        // For this step, we simply generate the generic slots from the availabilities.
        // Integration with actual `appointments` table for booking conflict checking will be enhanced in the checkout step.
        foreach ($availabilities as $availability) {
            $start = Carbon::parse($dateString . ' ' . $availability->start_time);
            $end = Carbon::parse($dateString . ' ' . $availability->end_time);

            while ($start->copy()->addMinutes($slotDuration)->lte($end)) {
                $slotTime = $start->format('H:i');

                // Exclude past times if the date is today
                if ($date->isToday() && $start->isPast()) {
                    $start->addMinutes($slotDuration);
                    continue;
                }

                $slots[] = $slotTime;
                $start->addMinutes($slotDuration);
            }
        }

        return $slots;
    }
}
