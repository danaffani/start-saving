<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sky: {
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            800: '#075985'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation Bar -->
        <nav class="bg-sky-300 shadow-md">
            <div class="max-w-full mx-auto px-4">
                <div class="flex justify-between h-16">
                    <!-- Logo and Toggle Button -->
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button id="sidebar-toggle" class="md:hidden mr-2 flex items-center justify-center p-2 rounded-md text-sky-800 hover:text-white hover:bg-sky-500 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Logo -->
                        <a class="flex-shrink-0 flex items-center text-sky-800 font-bold text-xl" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    <!-- Right Side Navigation Items -->
                    <div class="flex items-center">
                        <div class="hidden md:ml-6 md:flex md:items-center">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <a class="px-3 py-2 rounded-md text-sm font-medium text-sky-800 hover:bg-sky-400 hover:text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                                @endif

                                @if (Route::has('register'))
                                    <a class="ml-3 px-3 py-2 rounded-md text-sm font-medium text-sky-800 hover:bg-sky-400 hover:text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            @else
                                <!-- User Dropdown -->
                                <div class="ml-3 relative">
                                    <button id="user-menu-button" class="flex items-center text-sm font-medium text-sky-800 hover:text-sky-900 focus:outline-none">
                                        <span class="mr-2">{{ Auth::user()->name }}</span>
                                        <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div id="user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                        <div class="py-1">
                                            <a href="{{ route('logout') }}"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-sky-100"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-sky-800 hover:bg-sky-400 hover:text-white">{{ __('Login') }}</a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-sky-800 hover:bg-sky-400 hover:text-white">{{ __('Register') }}</a>
                        @endif
                    @else
                        <div class="px-3 py-2 text-base font-medium text-sky-800">{{ Auth::user()->name }}</div>
                        <a href="{{ route('logout') }}"
                           class="block px-3 py-2 rounded-md text-base font-medium text-sky-800 hover:bg-sky-400 hover:text-white"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    @endguest
                </div>
            </div>
        </nav>

        <div class="flex-1 flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div id="sidebar" class="hidden md:block md:w-64 bg-sky-100 border-r border-sky-200">
                <div class="h-full px-3 py-4 overflow-y-auto">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="flex items-center p-2 text-sky-800 rounded-lg hover:bg-sky-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="ml-3">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('category-groups.index') }}" class="flex items-center p-2 text-sky-800 rounded-lg hover:bg-sky-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="ml-3">Category Groups</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-sky-800 rounded-lg hover:bg-sky-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="ml-3">Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('saving-targets.index') }}" class="flex items-center p-2 text-sky-800 rounded-lg hover:bg-sky-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                <span class="ml-3">Saving Targets</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-2 text-sky-800 rounded-lg hover:bg-sky-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="ml-3">Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-2 text-sky-800 rounded-lg hover:bg-sky-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="ml-3">Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript for toggles -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar on mobile
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const mobileMenu = document.getElementById('mobile-menu');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Toggle user dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
