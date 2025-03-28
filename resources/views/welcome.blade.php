<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ArashExpress - твой личный помощник">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="manifest" href="/manifest.json">

    <title>{{ env('APP_NAME')  }}</title>

    @vite(['resources/css/bootstrap.min.css', 'resources/css/bootstrap-icons.css'])
</head>
<body style="overflow: auto !important;" id="mainBody">
<div id="app"></div>


<script>
    const body = document.getElementById('mainBody');

    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function () {
            if (body.style.cssText.length !== 0)
                body.style.cssText = '';
        });
    });

    observer.observe(body, {attributes: true, attributeFilter: ['style']});
</script>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>

@vite('resources/js/app.js')
<!-- project2024 -->
</html>
