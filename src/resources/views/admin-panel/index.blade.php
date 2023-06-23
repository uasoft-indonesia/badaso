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
        $api_prefix = env('VITE_API_ROUTE_PREFIX');
        $disk = config('badaso.storage.disk');
        $is_exists = Storage::disk($disk)->exists($favicon);
    ?>

    @if ($is_exists && $disk != 'public' && $disk != 'local')
        <link rel="shortcut icon" href="{{ Storage::disk($disk)->url($favicon) }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ asset('storage' . '/' . $favicon) }}" type="image/png">
    @endif

    @vite('vendor/badaso/core/src/resources/badaso/assets/scss/style.scss')
</head>
<body>
    <div id="app"></div>
    <script>
        window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
        window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
        window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;
    </script>
     @vite('vendor/badaso/core/src/resources/badaso/app.js')
</body>
</html>
