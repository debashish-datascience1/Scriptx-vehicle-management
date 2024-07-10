<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ trans('installer_messages.title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/installer/img/favicon/favicon-16x16.png') }}" sizes="16x16"/>
    <link rel="icon" type="image/png" href="{{ asset('assets/installer/img/favicon/favicon-32x32.png') }}" sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{ asset('assets/installer/img/favicon/favicon-96x96.png') }}" sizes="96x96"/>


<meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('assets/installer/css/style.min.css') }}" rel="stylesheet"/>
    @yield('style')

</head>
<body>
<div class="master">
    <div class="box" style="width: 50% !important;">
        <div class="header">
            <img src="{{ asset('/assets/images/logo_install.png') }}" height="55px" alt="">
            <h1 class="header__title">@yield('title')</h1>
        </div>

        <div class="main">
            @yield('container')
        </div>
    </div>
</div>
</body>
@yield('scripts')
</html>
