<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tusd Test</title>
</head>
<body>
    <form id="tusd_action" onsubmit="return false;">
        <input type="hidden" id="hide_url" value="{{ config('services.network.url') }}">
        <input type="file" name="test_file" id="test_file" />
    </form>
    <script src="{{ asset('js/tusd.js') }}"></script>
</body>
</html>