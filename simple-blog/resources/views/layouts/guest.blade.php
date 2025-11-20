<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        <div class="min-h-screen flex">
            <!-- Left Side - Form -->
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div class="mb-8">
                        <a href="/" class="text-2xl font-bold text-gray-900 tracking-tight">
                            My Blog
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>

            <!-- Right Side - Image/Pattern -->
            <div class="hidden lg:block relative flex-1 bg-gray-50">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="max-w-md text-center px-8">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">Welcome to My Blog</h2>
                        <p class="text-lg text-gray-600">
                            Discover stories, thinking, and expertise from writers on any topic.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
