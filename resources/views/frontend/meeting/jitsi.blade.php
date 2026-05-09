<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('frontend.join_meeting') }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #000;
        }
        #jitsi-container {
            width: 100%;
            height: 100%;
        }
    </style>
    <!-- Load the Jitsi External API -->
    <script src="https://meet.jit.si/external_api.js"></script>
</head>
<body>
    <div id="jitsi-container"></div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const domain = "meet.jit.si";
            const options = {
                roomName: "{{ $roomName }}",
                width: "100%",
                height: "100%",
                parentNode: document.querySelector('#jitsi-container'),
                userInfo: {
                    email: "{{ $user->email }}",
                    displayName: "{{ $user->name }}"
                },
                configOverwrite: {
                    startWithAudioMuted: false,
                    startWithVideoMuted: false
                },
                interfaceConfigOverwrite: {
                    DISABLE_JOIN_LEAVE_NOTIFICATIONS: true
                }
            };
            const api = new JitsiMeetExternalAPI(domain, options);
        });
    </script>
</body>
</html>
