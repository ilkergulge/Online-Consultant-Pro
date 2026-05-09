<?php

namespace App\Jobs;

use App\Mail\AppointmentReminderMail;
use App\Models\Appointment;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send Email
        Mail::to($this->appointment->client->email)->send(new AppointmentReminderMail($this->appointment));

        // Send SMS
        if ($this->appointment->client->phone) {
            $message = __('frontend.appointment_reminder_subject') . ': ' . $this->appointment->consultant->name . ' - ' . $this->appointment->scheduled_at->format('H:i');
            SmsService::sendSms($this->appointment->client->phone, $message);
        }
    }
}
