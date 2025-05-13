<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSavingTargetRequest;
use App\Http\Requests\UpdateSavingTargetRequest;
use App\Models\Category;
use App\Models\SavingTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SavingTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $savingTargets = SavingTarget::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            ->where('user_id', Auth::id())
            ->with(['savings']) // Load savings relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate progress for each saving target
        $savingTargets->each(function ($target) {
            $totalSaved = $target->savings->sum('amount');
            $target->progress = $target->amount > 0 ? min(100, round(($totalSaved / $target->amount) * 100)) : 0;
            $target->total_saved = $totalSaved;
        });

        // Fetch category groups with their categories
        $categoryGroups = \App\Models\CategoryGroup::with(['categories' => function ($query) {
            $query->where('user_id', Auth::id())->orderBy('name');
        }])
        ->where('user_id', Auth::id())
        ->orderBy('name')
        ->get();

        return view('app.saving-targets.index', compact('savingTargets', 'categoryGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // get all categories
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();

        // Check if a category_id was provided
        $selectedCategoryId = $request->query('category_id');

        return view('app.saving-targets.create', compact('categories', 'selectedCategoryId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSavingTargetRequest $request)
    {
        try {
            // Validate the request
            $validated = $request->validated();

            // Create the saving target
            $savingTarget = SavingTarget::make($validated);
            $savingTarget->user_id = Auth::id();
            $savingTarget->save();

            // Redirect to the index page with a success message
            return redirect()->route('saving-targets.index')->with(['status' => 'Saving target created successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to create saving target: ' . $e->getMessage());

            // Redirect to the create page with an error message
            return redirect()->route('saving-targets.create')->with(['status' => 'Failed to create saving target']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the savings relationship
        $savingTarget->load('savings');

        return view('app.saving-targets.show', compact('savingTarget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // get all categories for the current user
        $categories = Category::where('user_id', Auth::id())->orderBy('name')->get();

        return view('app.saving-targets.edit', compact('savingTarget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSavingTargetRequest $request, SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Validate the request
            $validated = $request->validated();

            // Update the saving target
            $savingTarget->update($validated);

            // Redirect to the index page with a success message
            return redirect()->route('saving-targets.index')->with(['status' => 'Saving target updated successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to update saving target: ' . $e->getMessage());

            // Redirect to the edit page with an error message
            return redirect()->route('saving-targets.edit', $savingTarget)->with(['status' => 'Failed to update saving target']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete the saving target
            $savingTarget->delete();

            // Redirect to the index page with a success message
            return redirect()->route('saving-targets.index')->with(['status' => 'Saving target deleted successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to delete saving target: ' . $e->getMessage());

            // Redirect to the index page with an error message
            return redirect()->route('saving-targets.index')->with(['status' => 'Failed to delete saving target']);
        }
    }
}
