<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ConsultantProfileRepository;

class ConsultantProfileService
{
    protected $profileRepository;

    public function __construct(ConsultantProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function handleUpdate(User $user, array $data)
    {
        return $this->profileRepository->updateProfile($user, $data);
    }
}
