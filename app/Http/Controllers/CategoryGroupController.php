<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryGroupRequest;
use App\Http\Requests\UpdateCategoryGroupRequest;
use App\Models\CategoryGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryGroups = CategoryGroup::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('app.category-groups.index', compact('categoryGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.category-groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryGroupRequest $request)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Create the category group
            $categoryGroup = CategoryGroup::make($validated);
            $categoryGroup->user_id = Auth::id();
            $categoryGroup->save();

            // Redirect to the index page with a success message
            return redirect()->route('category-groups.index')->with(['status' => 'Category group created successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to create category group: ' . $e->getMessage());

            // Redirect to the create page with an error message
            return redirect()->route('category-groups.create')->with(['status' => 'Failed to create category group']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryGroup $categoryGroup)
    {
        return view('app.category-groups.show', compact('categoryGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryGroup $categoryGroup)
    {
        return view('app.category-groups.edit', compact('categoryGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryGroupRequest $request, CategoryGroup $categoryGroup)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Update the category group
            $categoryGroup->update($validated);

            // Redirect to the index page with a success message
            return redirect()->route('category-groups.index')->with(['status' => 'Category group updated successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to update category group: ' . $e->getMessage());

            // Redirect to the edit page with an error message
            return redirect()->route('category-groups.edit', $categoryGroup)->with(['status' => 'Failed to update category group']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryGroup $categoryGroup)
    {
        try {
            // Delete the category group
            $categoryGroup->delete();

            // Redirect to the index page with a success message
            return redirect()->route('category-groups.index')->with(['status' => 'Category group deleted successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to delete category group: ' . $e->getMessage());

            // Redirect to the index page with an error message
            return redirect()->route('category-groups.index')->with(['status' => 'Failed to delete category group']);
        }
    }
}
