@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Savings for {{ $savingTarget->name }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('saving-targets.savings.create', $savingTarget) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Savings
                </a>
                <a href="{{ route('saving-targets.show', $savingTarget) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Target
                </a>
            </div>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Target Summary -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-md">
            <div class="px-4 py-5 sm:px-6 bg-sky-100">
                <h2 class="text-lg font-medium text-gray-900">Target Summary</h2>
            </div>
            <div class="border-t border-gray-200 p-4">
                <div class="flex flex-col md:flex-row md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-500">Target Amount</p>
                        <p class="text-lg font-semibold">Rp{{ number_format($savingTarget->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-500">Total Saved</p>
                        <p class="text-lg font-semibold">
                            @php
                                $totalSaved = $savingTarget->savings->sum('amount');
                            @endphp
                            Rp{{ number_format($totalSaved, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Progress</p>
                        <p class="text-lg font-semibold">
                            @php
                                $progress = $savingTarget->amount > 0 ? min(100, round(($totalSaved / $savingTarget->amount) * 100)) : 0;
                            @endphp
                            {{ $progress }}%
                        </p>
                    </div>
                </div>
                <div class="mt-4 w-full bg-gray-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full {{ $progress >= 100 ? 'bg-green-600' : 'bg-sky-600' }}" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        <!-- Savings List -->
        <div class="mt-6 bg-white shadow overflow-hidden rounded-md">
            <div class="px-4 py-5 sm:px-6 bg-sky-100">
                <h2 class="text-lg font-medium text-gray-900">Savings History</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($savings as $saving)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $saving->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">
                                    Rp{{ number_format($saving->amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="{{ route('saving-targets.savings.edit', [$savingTarget, $saving]) }}" class="text-sky-600 hover:text-sky-900" title="Edit">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('saving-targets.savings.destroy', [$savingTarget, $saving]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this saving?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                No savings recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $savings->links() }}
        </div>
    </div>
</div>
@endsection
