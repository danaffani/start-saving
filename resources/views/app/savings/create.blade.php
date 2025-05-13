@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Add Savings to {{ $savingTarget->name }}</h1>
            <a href="{{ route('saving-targets.show', $savingTarget) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Target
            </a>
        </div>

        <!-- Target Summary -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-md">
            <div class="px-4 py-5 sm:px-6 bg-sky-100">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Target Summary</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $savingTarget->category->name ?? 'No Category' }}</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-3">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Target Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">Rp{{ number_format($savingTarget->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Total Saved</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            @php
                                $totalSaved = $savingTarget->savings->sum('amount');
                            @endphp
                            Rp{{ number_format($totalSaved, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Remaining</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                            @php
                                $remaining = max(0, $savingTarget->amount - $totalSaved);
                            @endphp
                            Rp{{ number_format($remaining, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
            <form action="{{ route('saving-targets.savings.store', $savingTarget) }}" method="POST" class="p-6">
                @csrf

                <!-- Status Messages -->
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Amount Input -->
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount (Rp)</label>
                    <input type="number" step="1" min="0" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md px-4 py-2" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
