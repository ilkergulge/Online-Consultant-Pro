<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['consultant_id', 'day_of_week', 'start_time', 'end_time'];

    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }
}
