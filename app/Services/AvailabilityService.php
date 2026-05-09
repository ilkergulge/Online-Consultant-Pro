<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AvailabilityRepository;

class AvailabilityService
{
    protected $availabilityRepository;

    public function __construct(AvailabilityRepository $availabilityRepository)
    {
        $this->availabilityRepository = $availabilityRepository;
    }

    public function handleCreate(User $user, array $data)
    {
        $data['consultant_id'] = $user->id;
        return $this->availabilityRepository->create($data);
    }

    public function handleDelete(User $user, int $id)
    {
        return $this->availabilityRepository->delete($user, $id);
    }
}
