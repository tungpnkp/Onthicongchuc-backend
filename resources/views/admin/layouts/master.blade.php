<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        <!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Volgh –  Bootstrap 4 Responsive Application Admin panel Theme Ui Kit & Premium Dashboard Design Modern Flat HTML Template">
        <meta name="author" content="Spruko Technologies Private Limited">
        <meta name="keywords" content="analytics dashboard, bootstrap 4 web app admin template, bootstrap admin panel, bootstrap admin template, bootstrap dashboard, bootstrap panel, Application dashboard design, dashboard design template, dashboard jquery clean html, dashboard template theme, dashboard responsive ui, html admin backend template ui kit, html flat dashboard template, it admin dashboard ui, premium modern html template">
        @include('admin.layouts.head')
    </head>

    <body class="app sidebar-mini">
        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            <img src="{{URL::asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOBAL-LOADER -->
        <!-- PAGE -->
         <div class="page">
         <div class="page-main">
            @include('admin.layouts.app-sidebar')
            @include('admin.layouts.mobile-header')
        <div class="app-content">
        <div class="side-app">
        <div class="page-header">
            @yield('page-header')
{{--            @include('admin.layouts.notification')--}}
        </div>
            @yield('content')
            @include('admin.layouts.sidebar')
            @include('admin.layouts.footer')
        </div>
        </div>
            @include('admin.layouts.footer-scripts')
    </body>
</html>
