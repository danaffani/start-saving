<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSavingRequest;
use App\Http\Requests\UpdateSavingRequest;
use App\Models\Saving;
use App\Models\SavingTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SavingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $savings = $savingTarget->savings()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('app.savings.index', compact('savings', 'savingTarget'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('app.savings.create', compact('savingTarget'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SavingTarget $savingTarget)
    {
        // Check if the saving target belongs to the authenticated user
        if ($savingTarget->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $saving = new Saving([
                'amount' => $validated['amount'],
                'user_id' => Auth::id(),
                'saving_target_id' => $savingTarget->id,
            ]);

            $saving->save();

            return redirect()->route('saving-targets.show', $savingTarget)
                ->with('status', 'Savings added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create saving: ' . $e->getMessage());
            return redirect()->back()->with('status', 'Failed to add savings.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SavingTarget $savingTarget, Saving $saving)
    {
        // Check if the saving and saving target belong to the authenticated user
        if ($savingTarget->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('app.savings.show', compact('saving', 'savingTarget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SavingTarget $savingTarget, Saving $saving)
    {
        // Check if the saving and saving target belong to the authenticated user
        if ($savingTarget->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('app.savings.edit', compact('saving', 'savingTarget'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SavingTarget $savingTarget, Saving $saving)
    {
        // Check if the saving and saving target belong to the authenticated user
        if ($savingTarget->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $saving->amount = $validated['amount'];
            $saving->save();

            return redirect()->route('saving-targets.savings.index', $savingTarget)
                ->with('status', 'Saving updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update saving: ' . $e->getMessage());
            return redirect()->back()->with('status', 'Failed to update saving.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingTarget $savingTarget, Saving $saving)
    {
        // Check if the saving and saving target belong to the authenticated user
        if ($savingTarget->user_id !== Auth::id() || $saving->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $saving->delete();

            return redirect()->route('saving-targets.savings.index', $savingTarget)
                ->with('status', 'Saving deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete saving: ' . $e->getMessage());
            return redirect()->back()->with('status', 'Failed to delete saving.');
        }
    }
}
