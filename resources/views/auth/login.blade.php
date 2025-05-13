@extends('layouts.auth')

@section('title', 'Login')
@section('page_heading', 'Sign in to your account')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
        <div class="mt-1">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm @error('email') border-red-500 @enderror">
        </div>
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
        <div class="mt-1">
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm @error('password') border-red-500 @enderror">
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                   class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-900">
                {{ __('Remember Me') }}
            </label>
        </div>

        @if (Route::has('password.request'))
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-sky-600 hover:text-sky-500">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        @endif
    </div>

    <div>
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
            {{ __('Login') }}
        </button>
    </div>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">{{ __('Or continue with') }}</span>
        </div>
    </div>

    <!-- Google OAuth Button -->
    <div>
        <a href="{{ route('login.google', ['source' => 'login']) }}"
           class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
                    <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
                    <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
                    <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
                </g>
            </svg>
            {{ __('Sign in with Google') }}
        </a>
    </div>

    @if (Route::has('register'))
        <div class="text-sm text-center mt-6">
            <p class="text-gray-600">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-medium text-sky-600 hover:text-sky-500">
                    {{ __('Register now') }}
                </a>
            </p>
        </div>
    @endif
</form>
@endsection
