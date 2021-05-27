<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badaso</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

    <!-- Favicon -->
    <?php
        use Uasoft\Badaso\Helpers\Config;

        $favicon = Config::get('favicon');
        $api_prefix = env('MIX_API_ROUTE_PREFIX');
    ?>

    @if(!$favicon || $favicon == '')
        <link rel="shortcut icon" href="{{ asset('badaso-images/logo-144px.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{'/'.$api_prefix.'/v1/file/view?file='.$favicon}}" type="image/png">
    @endif
</head>
<body>
    <div id="app"></div>
    <script>
        window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
        window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
        window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;
    </script>
    <script src="{{ mix('js/badaso.js') }}"></script>
</body>
</html>