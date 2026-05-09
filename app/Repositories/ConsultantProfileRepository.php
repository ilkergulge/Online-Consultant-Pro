<?php

namespace App\Repositories;

use App\Models\User;

class ConsultantProfileRepository
{
    public function getProfile(User $user)
    {
        return $user->consultantProfile()->firstOrNew(['user_id' => $user->id]);
    }

    public function updateProfile(User $user, array $data)
    {
        $profile = $this->getProfile($user);
        $profile->fill($data);
        $profile->save();
        return $profile;
    }
}
