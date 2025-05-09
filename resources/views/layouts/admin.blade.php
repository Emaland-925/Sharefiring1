<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ShareFiring') }} - {{ __('messages.admin_panel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/themes/orange.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .rtl {
            direction: rtl;
            text-align: right;
        }
        
        .ltr {
            direction: ltr;
            text-align: left;
        }
        
        .admin-sidebar {
            min-height: calc(100vh - 64px);
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div id="app">
        @include('components.header')

        <main>
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap">
                    <div class="w-full md:w-1/4 lg:w-1/5 p-4">
                        @include('components.admin-sidebar')
                    </div>
                    <div class="w-full md:w-3/4 lg:w-4/5 p-4">
                        @include('components.alerts')
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
        
        @include('components.footer')
    </div>
    
    @yield('scripts')
</body>
</html>