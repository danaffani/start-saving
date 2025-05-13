<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\CategoryGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('app.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get all category groups
        $categoryGroups = CategoryGroup::orderBy('name')->get();
        
        return view('app.categories.create', compact('categoryGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Create the category
            $category = Category::make($validated);
            $category->save();

            // Redirect to the index page with a success message
            return redirect()->route('categories.index')->with(['status' => 'Category created successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to create category: ' . $e->getMessage());

            // Redirect to the create page with an error message
            return redirect()->route('categories.create')->with(['status' => 'Failed to create category']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('app.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // get all category groups
        $categoryGroups = CategoryGroup::orderBy('name')->get();

        return view('app.categories.edit', compact('category', 'categoryGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Update the category
            $category->update($validated);

            // Redirect to the index page with a success message
            return redirect()->route('categories.index')->with(['status' => 'Category updated successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to update category: ' . $e->getMessage());

            // Redirect to the edit page with an error message
            return redirect()->route('categories.edit', $category)->with(['status' => 'Failed to update category']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Delete the category
            $category->delete();

            // Redirect to the index page with a success message
            return redirect()->route('categories.index')->with(['status' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to delete category: ' . $e->getMessage());

            // Redirect to the index page with an error message
            return redirect()->route('categories.index')->with(['status' => 'Failed to delete category']);
        }
    }
}
