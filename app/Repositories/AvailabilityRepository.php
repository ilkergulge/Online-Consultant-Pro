<?php

namespace App\Repositories;

use App\Models\Availability;
use App\Models\User;

class AvailabilityRepository
{
    public function getAvailability(User $user)
    {
        return $user->availabilities()->orderBy('day_of_week')->orderBy('start_time')->get();
    }

    public function create(array $data)
    {
        return Availability::create($data);
    }

    public function delete(User $user, int $id)
    {
        return $user->availabilities()->where('id', $id)->delete();
    }
}
