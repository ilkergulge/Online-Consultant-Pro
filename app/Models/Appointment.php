<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'consultant_id',
        'scheduled_at',
        'duration_minutes',
        'status',
        'meeting_link',
        'reminder_24h_sent',
        'reminder_1h_sent',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
