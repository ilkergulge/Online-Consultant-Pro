<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} Consultant</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="w-64 bg-white shadow-md">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('consultant.consultant_panel') }}</h2>
                </div>
                <nav class="mt-6">
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-700 bg-gray-200" href="{{ route('consultant.dashboard') }}">
                        <span class="mx-3">{{ __('consultant.dashboard') }}</span>
                    </a>
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-600 hover:bg-gray-200 hover:text-gray-700" href="{{ route('consultant.profile.edit') }}">
                        <span class="mx-3">{{ __('consultant.profile') }}</span>
                    </a>
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-600 hover:bg-gray-200 hover:text-gray-700" href="{{ route('consultant.availability.index') }}">
                        <span class="mx-3">{{ __('consultant.availability') }}</span>
                    </a>
                </nav>
            </div>

            <!-- Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="flex justify-between items-center p-6 bg-white shadow-md">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-800">
                            @yield('header')
                        </h2>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
