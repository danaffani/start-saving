@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard</h1>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Savings -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-sky-400">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-sky-100 mr-4">
                        <svg class="h-8 w-8 text-sky-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Savings</p>
                        <p class="text-2xl font-semibold text-gray-700">
                            @php
                                $totalSavings = App\Models\Saving::where('user_id', Auth::id())->sum('amount');
                            @endphp
                            Rp{{ number_format($totalSavings, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Active Targets -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-400">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Active Targets</p>
                        <p class="text-2xl font-semibold text-gray-700">
                            @php
                                $targetCount = App\Models\SavingTarget::where('user_id', Auth::id())->count();
                            @endphp
                            {{ $targetCount }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-lg shadow p-6 border-t-4 border-purple-400">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 mr-4">
                        <svg class="h-8 w-8 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Categories</p>
                        <p class="text-2xl font-semibold text-gray-700">
                            @php
                                $categoryCount = App\Models\Category::where('user_id', Auth::id())->count();
                            @endphp
                            {{ $categoryCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Saving Targets Progress -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-sky-50">
                <h2 class="text-lg font-medium text-gray-900">Saving Targets Progress</h2>
            </div>
            <div class="p-6">
                @php
                    $savingTargets = App\Models\SavingTarget::with(['savings', 'category'])
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if(count($savingTargets) > 0)
                    <div class="space-y-6">
                        @foreach($savingTargets as $target)
                            @php
                                $totalSaved = $target->savings->sum('amount');
                                $progress = $target->amount > 0 ? min(100, round(($totalSaved / $target->amount) * 100)) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between mb-1">
                                    <div>
                                        <a href="{{ route('saving-targets.show', $target) }}" class="text-sky-600 hover:text-sky-800 font-medium">{{ $target->name }}</a>
                                        <span class="text-xs text-gray-500 ml-2">{{ $target->category->name ?? 'No Category' }}</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="font-medium {{ $progress >= 100 ? 'text-green-600' : 'text-sky-600' }}">{{ $progress }}%</span>
                                        <span class="text-gray-500"> • Rp{{ number_format($totalSaved, 0, ',', '.') }} / Rp{{ number_format($target->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $progress >= 100 ? 'bg-green-600' : 'bg-sky-600' }}" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('saving-targets.index') }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium">View all targets →</a>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2">No saving targets yet.</p>
                        <a href="{{ route('saving-targets.create') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700">
                            Create your first target
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Category Groups and Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Savings Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-sky-50">
                    <h2 class="text-lg font-medium text-gray-900">Recent Savings Activity</h2>
                </div>
                <div class="p-6">
                    @php
                        $recentSavings = App\Models\Saving::with('savingTarget')
                            ->where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp

                    @if(count($recentSavings) > 0)
                        <ul class="divide-y divide-gray-200">
                            @foreach($recentSavings as $saving)
                                <li class="py-3 flex justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $saving->savingTarget->name ?? 'Unknown Target' }}</p>
                                        <p class="text-xs text-gray-500">{{ $saving->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="text-sm font-semibold text-sky-600">
                                        Rp{{ number_format($saving->amount, 0, ',', '.') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <p>No savings activity yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Categories by Group -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-sky-50">
                    <h2 class="text-lg font-medium text-gray-900">Categories by Group</h2>
                </div>
                <div class="p-6">
                    @php
                        $categoryGroups = App\Models\CategoryGroup::with(['categories' => function($query) {
                            $query->where('user_id', Auth::id());
                        }])
                        ->where('user_id', Auth::id())
                        ->take(3)
                        ->get();
                    @endphp

                    @if(count($categoryGroups) > 0)
                        <div class="space-y-4">
                            @foreach($categoryGroups as $group)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-2">{{ $group->name }}</h3>
                                    @if(count($group->categories) > 0)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($group->categories as $category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-500">No categories in this group.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-right">
                            <a href="{{ route('categories.index') }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium">Manage categories →</a>
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <p>No category groups yet.</p>
                            <a href="{{ route('category-groups.create') }}" class="mt-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700">
                                Create your first group
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
