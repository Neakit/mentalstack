<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MentalStack</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href=" {{ mix('css/app.css', '/web') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <App />
    </div>
    <script src="{{ mix('js/entry.js', '/web') }}"></script>
</body>
</html>
