<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
        <link rel="shortcut icon" href="{{ asset('badaso-images/badaso-logo.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{'/'.$api_prefix.'/v1/file/view?file='.$favicon}}" type="image/png">
    @endif
</head>
  <body>
      <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae nulla explicabo ullam ab nostrum, facere quaerat maiores culpa quia quibusdam minus amet repudiandae itaque repellat, voluptate beatae excepturi optio mollitia!</p>
  </body>
</html>