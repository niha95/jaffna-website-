<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="_token" content="{{ csrf_token() }}">

    <title>@yield('title', trans('labels.control_panel'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/control-panel/images/favicon-cogs.ico') }}" />

    <!-- Main Stylesheets -->
    <link href="{{ asset('assets/control-panel/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/control-panel/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/control-panel/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/control-panel/css/toastr.min.css') }}" rel="stylesheet">

    @if(app()->getLocale() == 'ar')
        <link href="http://cdn.jsdelivr.net/darfonts/0.1/tah-van-sud-tun/stylesheet.css" rel="stylesheet"/>
        <link rel="stylesheet" href="{{ asset('assets/control-panel/css/bootstrap-flipped.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/main.css?v=2') }}">

    @if(\app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/control-panel/css/main-ar.css') }}">
    @endif

    <!-- Custom CSS -->
    @yield('css')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')
</head>