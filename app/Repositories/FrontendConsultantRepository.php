<?php

namespace App\Repositories;

use App\Models\User;

class FrontendConsultantRepository
{
    public function getFilteredConsultants(array $filters)
    {
        $query = User::where('role', 'consultant')->with(['consultantProfile.category']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('consultantProfile', function ($q2) use ($filters) {
                      $q2->where('title', 'like', '%' . $filters['search'] . '%')
                         ->orWhere('bio', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('consultantProfile.category', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        if (!empty($filters['min_price'])) {
            $query->whereHas('consultantProfile', function ($q) use ($filters) {
                $q->where('hourly_rate', '>=', $filters['min_price']);
            });
        }

        if (!empty($filters['max_price'])) {
            $query->whereHas('consultantProfile', function ($q) use ($filters) {
                $q->where('hourly_rate', '<=', $filters['max_price']);
            });
        }

        return $query->paginate(12);
    }
}
