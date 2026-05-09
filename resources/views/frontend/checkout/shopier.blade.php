<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Redirecting to Shopier...</title>
</head>
<body onload="document.forms[0].submit()">
    <div style="text-align: center; margin-top: 50px; font-family: sans-serif;">
        <p>Redirecting to secure payment gateway. Please wait...</p>
        <form action="https://www.shopier.com/ShowProduct/api_pay4.php" method="post">
            @foreach($args as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <noscript>
                <button type="submit">Click here if you are not redirected</button>
            </noscript>
        </form>
    </div>
</body>
</html>
