<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendAppointmentReminderJob;
use App\Models\Appointment;
use Carbon\Carbon;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send 24 hour and 1 hour reminders for appointments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // 24 Hour Reminders
        $target24hStart = $now->copy()->addHours(24)->startOfMinute();
        $target24hEnd = $now->copy()->addHours(24)->addMinutes(5)->endOfMinute();

        $appointments24h = Appointment::where('status', 'confirmed')
            ->whereBetween('scheduled_at', [$target24hStart, $target24hEnd])
            ->where('reminder_24h_sent', false)
            ->get();

        foreach ($appointments24h as $appointment) {
            SendAppointmentReminderJob::dispatch($appointment);
            $appointment->update(['reminder_24h_sent' => true]);
        }

        // 1 Hour Reminders
        $target1hStart = $now->copy()->addHour()->startOfMinute();
        $target1hEnd = $now->copy()->addHour()->addMinutes(5)->endOfMinute();

        $appointments1h = Appointment::where('status', 'confirmed')
            ->whereBetween('scheduled_at', [$target1hStart, $target1hEnd])
            ->where('reminder_1h_sent', false)
            ->get();

        foreach ($appointments1h as $appointment) {
            SendAppointmentReminderJob::dispatch($appointment);
            $appointment->update(['reminder_1h_sent' => true]);
        }

        $this->info("Dispatched " . ($appointments24h->count() + $appointments1h->count()) . " reminder jobs.");
    }
}
