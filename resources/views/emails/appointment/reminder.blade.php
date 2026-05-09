<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { background-color: #fef9c3; padding: 10px 20px; text-align: center; border-bottom: 2px solid #eab308; }
        .content { padding: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #eab308; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #854d0e;">{{ __('frontend.appointment_reminder_subject') }}</h2>
        </div>
        <div class="content">
            <p>{{ __('frontend.hello') }}, {{ $appointment->client->name }}!</p>
            <p>{{ __('frontend.appointment_reminder_message') }}</p>

            <ul>
                <li><strong>{{ __('frontend.consultant') }}:</strong> {{ $appointment->consultant->name }}</li>
                <li><strong>{{ __('frontend.date') }}:</strong> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') }}</li>
                <li><strong>{{ __('frontend.time') }}:</strong> {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('H:i') }}</li>
            </ul>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ $appointment->meeting_link }}" class="btn">{{ __('frontend.join_meeting') }}</a>
            </p>

            <p>{{ __('frontend.thank_you') }}<br>{{ config('app.name') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
