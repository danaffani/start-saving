@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Saving Targets</h1>
            <a href="{{ route('saving-targets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New
            </a>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Search Form -->
        <div class="mt-6 mb-4">
            <form action="{{ route('saving-targets.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Search saving targets..." class="px-4 py-2 shadow-sm focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
                @if (request()->has('search'))
                    <a href="{{ route('saving-targets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6">
            <!-- Categories By Group Section -->
            <div class="bg-white shadow overflow-hidden rounded-md">
                <div class="px-4 py-5 sm:px-6 bg-sky-100">
                    <h2 class="text-lg font-medium text-gray-900">Categories</h2>
                    <p class="mt-1 text-sm text-gray-600">Choose a category to add a new saving target.</p>
                </div>

                @if(count($categoryGroups) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                        @foreach($categoryGroups as $group)
                            <div class="bg-white border border-sky-200 rounded-lg overflow-hidden shadow-sm">
                                <div class="px-4 py-3 bg-sky-100 border-b border-sky-200">
                                    <h3 class="text-md font-medium text-sky-800">{{ $group->name }}</h3>
                                </div>
                                <div class="p-4">
                                    @if(count($group->categories) > 0)
                                        <ul class="divide-y divide-gray-200">
                                            @foreach($group->categories as $category)
                                                <li class="py-3 flex justify-between items-center">
                                                    <span class="text-sm text-gray-800">{{ $category->name }}</span>
                                                    <a href="{{ route('saving-targets.create', ['category_id' => $category->id]) }}"
                                                       class="inline-flex items-center p-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500"
                                                       title="Add saving target for {{ $category->name }}">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-500 py-2">No categories in this group.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-4 py-5 sm:p-6">
                        <p class="text-sm text-gray-500">No category groups found. <a href="{{ route('category-groups.create') }}" class="text-sky-600 hover:text-sky-800">Create a category group</a> first.</p>
                    </div>
                @endif
            </div>

            <!-- Saving Targets List Section -->
            <div class="bg-white shadow overflow-hidden rounded-md">
                <div class="px-4 py-5 sm:px-6 bg-sky-100">
                    <h2 class="text-lg font-medium text-gray-900">Current Saving Targets</h2>
                    <p class="mt-1 text-sm text-gray-600">List of your active saving targets.</p>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progress
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date Range
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($savingTargets as $savingTarget)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $savingTarget->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $savingTarget->category->name ?? 'None' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Rp{{ number_format($savingTarget->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-xs font-medium text-gray-700">{{ $savingTarget->progress }}%</span>
                                            <span class="text-xs font-medium text-gray-700">Rp{{ number_format($savingTarget->total_saved, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full {{ $savingTarget->progress >= 100 ? 'bg-green-600' : 'bg-sky-600' }}" style="width: {{ $savingTarget->progress }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $savingTarget->start_date->format('M d, Y') }} - {{ $savingTarget->end_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('saving-targets.savings.create', $savingTarget) }}" class="text-green-600 hover:text-green-900" title="Add Savings">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('saving-targets.edit', $savingTarget) }}" class="text-sky-600 hover:text-sky-900" title="Edit">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('saving-targets.destroy', $savingTarget) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this saving target?');">
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
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No saving targets found.
                                    @if (request()->has('search'))
                                        <a href="{{ route('saving-targets.index') }}" class="text-sky-600 hover:text-sky-900">Clear search</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $savingTargets->links() }}
        </div>
    </div>
</div>
@endsection
