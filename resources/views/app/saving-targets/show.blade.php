@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Saving Target Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('saving-targets.savings.create', $savingTarget) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Savings
                </a>
                <a href="{{ route('saving-targets.savings.index', $savingTarget) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    View Savings
                </a>
                <a href="{{ route('saving-targets.edit', $savingTarget) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('saving-targets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-sky-100">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $savingTarget->name }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $savingTarget->category->name ?? 'No Category' }} | Created {{ $savingTarget->created_at->format('M d, Y') }}</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $savingTarget->category->name ?? 'None' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Category Group</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $savingTarget->category->category_group->name ?? 'None' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">Rp{{ number_format($savingTarget->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Time Period</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $savingTarget->start_date->format('M d, Y') }} - {{ $savingTarget->end_date->format('M d, Y') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Progress</dt>
                        <dd class="mt-1">
                            <div class="flex flex-col">
                                <div class="flex justify-between mb-1">
                                    @php
                                        $totalSaved = $savingTarget->savings->sum('amount');
                                        $progress = $savingTarget->amount > 0 ? min(100, round(($totalSaved / $savingTarget->amount) * 100)) : 0;
                                    @endphp
                                    <span class="text-sm font-medium text-gray-700">{{ $progress }}% Complete</span>
                                    <span class="text-sm font-medium text-gray-700">Rp{{ number_format($totalSaved, 0, ',', '.') }} / Rp{{ number_format($savingTarget->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                                    <div class="h-2.5 rounded-full {{ $progress >= 100 ? 'bg-green-600' : 'bg-sky-600' }}" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $savingTarget->description ?? 'No description provided.' }}</dd>
                    </div>
                </dl>
            </div>
            <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
                <form action="{{ route('saving-targets.destroy', $savingTarget) }}" method="POST" class="flex justify-end" onsubmit="return confirm('Are you sure you want to delete this saving target?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Saving Target
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
