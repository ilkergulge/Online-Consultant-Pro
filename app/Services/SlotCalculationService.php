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

        $bookedSlots = $consultant->appointments()
            ->whereDate('scheduled_at', $dateString)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('scheduled_at')
            ->map(function($date) {
                return Carbon::parse($date)->format('H:i');
            })->toArray();

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

                if (!in_array($slotTime, $bookedSlots)) {
                    $slots[] = $slotTime;
                }

                $start->addMinutes($slotDuration);
            }
        }

        return $slots;
    }
}
