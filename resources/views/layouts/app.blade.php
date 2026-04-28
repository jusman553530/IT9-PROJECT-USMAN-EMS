<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'EMS - Employee Management System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Top Navigation Bar -->
    @include('layouts.navigation')

    
    <div class="flex">
        <!-- Sidebar -->
        @if(auth()->user()->isAdmin())
    @include('layouts.sidebar-admin')
@elseif(auth()->user()->isAccountant())
    @include('layouts.sidebar-accountant')
@else
    @include('layouts.sidebar-employee')
@endif
        <!-- Main Content Area -->
        <main class="flex-1 min-h-screen w-full lg:w-auto">
            <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>