<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Twitter -->
    <meta name="twitter:site" content="@Maulihsan">
    <meta name="twitter:creator" content="@Maulihsan">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Lab App">
    <meta name="twitter:description" content="Responsive Bootstrap 4 Dashboard Template">
    <meta name="twitter:image" content="https://via.placeholder.com/1260x950?text=Lab App">

    <!-- Facebook -->
    <meta property="og:url" content="{{ url('') }}">
    <meta property="og:title" content="Lab App">
    <meta property="og:description" content="Lab App - Web Backend Generate">

    <meta property="og:image" content="https://via.placeholder.com/1260x950?text=Lab App">
    <meta property="og:image:secure_url" content="https://via.placeholder.com/1260x950?text=Lab App">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Lab App - Web Backend Generate">
    <meta name="author" content="Maulihsan">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="manifest" href="/site.webmanifest">

    <title>Applikasi Manajemen Lab</title>

    <!-- vendor css -->


    @yield('css')
</head>

<body>
    @yield('contents')

    @yield('js')
</body>

</html>
