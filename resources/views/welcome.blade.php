<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('undip.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('undip.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('undip.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('undip.ico') }}">
    {{-- <link rel="manifest" href="site.webmanifest"> --}}

    <title>Gizi Lab Monitoring</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fonts/Raleway.css') }}" rel="stylesheet" type="text/css">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> --}}

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        /* html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            } */

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            /* font-size: 13px; */
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    {{-- <a href="{{ route('register') }}">Register</a> --}}
                @endauth
            </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                <!-- {{ isset($appName) ? $appName : 'Lab App' }} -->
                <img src="{{ asset('S1-Gizi.png') }}" alt="Lab App" width="325">
            </div>

            <div class="links">
                {{-- <a href="https://dandisy.github.io/1.0.4/documentation.html">Documentation</a>
                    <a href="https://dandisy.github.io/1.0.4/start.html">Quick Start</a>
                    <a href="https://dandisy.github.io/">Site</a>
                    <a href="https://github.com/dandisy/Lab App-sample">Sample Backend</a>
                    <a href="https://github.com/dandisy/Lab App-cms">CMS</a> --}}
            </div>
        </div>
    </div>
</body>

</html>
