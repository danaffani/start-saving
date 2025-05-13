@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Category</h1>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>

        <!-- Status Messages -->
        @if (session('status'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="category_group_id" class="block text-sm font-medium text-gray-700">Category Group</label>
                            <div class="mt-1">
                                <select id="category_group_id" name="category_group_id" class="px-4 py-2 shadow-sm focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md @error('category_group_id') border-red-300 @enderror">
                                    <option value="">Select a category group</option>
                                    @foreach($categoryGroups as $categoryGroup)
                                        <option value="{{ $categoryGroup->id }}" {{ (old('category_group_id', $category->category_group_id) == $categoryGroup->id) ? 'selected' : '' }}>
                                            {{ $categoryGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_group_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="px-4 py-2 shadow-sm focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <div class="mt-1">
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" class="px-4 py-2 shadow-sm focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md @error('slug') border-red-300 @enderror">
                                <p class="mt-1 text-xs text-gray-500">The slug will be used in URLs. It should contain only lowercase letters, numbers, and hyphens.</p>
                            </div>
                            @error('slug')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3" class="px-4 py-2 shadow-sm focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border border-gray-300 rounded-md @error('description') border-red-300 @enderror">{{ old('description', $category->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Slug Generation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let slugModified = false;

        // Track if slug has been manually modified
        slugInput.addEventListener('input', function() {
            slugModified = true;
        });

        // Auto-generate slug from name only if slug hasn't been modified
        nameInput.addEventListener('input', function() {
            if (!slugModified) {
                const nameValue = this.value;
                const slugValue = nameValue
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Remove special chars except spaces and hyphens
                    .replace(/\s+/g, '-')     // Replace spaces with hyphens
                    .replace(/-+/g, '-');     // Remove consecutive hyphens

                slugInput.value = slugValue;
            }
        });
    });
</script>
@endsection
