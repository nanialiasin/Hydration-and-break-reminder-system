<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HydrationController extends Controller
{
    /**
     * Show the guide for calculating average sip size.
     */
    public function showGuide()
    {
        return view('hydration.calculate-sips');
    }

    /**
     * After finishing calculation → go to home
     */

    public function finishCalculation(Request $request)
    {
        $athlete = \App\Models\Athlete::where('email', auth()->user()->email)->first();

        // Save as draft (partial/incomplete)
        if ($request->has('save_draft')) {
            session([
                'draft_empty_weight' => $request->empty_weight,
                'draft_filled_weight' => $request->filled_weight,
                'draft_sip_count' => $request->sip_count,
                'draft_remaining_weight' => $request->remaining_weight,
            ]);
            return back()->with('status', 'Draft saved! You can continue later.');
        }

        // Final submission: require all fields
        $request->validate([
            'empty_weight' => 'required|numeric',
            'filled_weight' => 'required|numeric',
            'sip_count' => 'required|numeric',
            'remaining_weight' => 'required|numeric',
        ]);

        // --- Calculate average mL per sip ---
        $empty = $request->empty_weight;
        $filled = $request->filled_weight;
        $remaining = $request->remaining_weight;
        $sips = $request->sip_count;

        $totalKg = $filled - $empty;
        $consumedKg = $totalKg - ($remaining - $empty);
        $consumedMl = $consumedKg * 1000;
        $avgMlPerSip = ($sips > 0 && $consumedMl > 0) ? ($consumedMl / $sips) : 0;

        // Save to athlete (adjust field name as needed)
        if ($athlete) {
            $athlete->weekly_avg = round($avgMlPerSip, 1); // or whatever field you use
            $athlete->sip_prompt_seen = true;
            $athlete->save();
        }

        // Clear draft from session
        session()->forget([
            'draft_empty_weight',
            'draft_filled_weight',
            'draft_sip_count',
            'draft_remaining_weight',
        ]);

        return redirect()->route('home')->with('success', 'Weekly Avg updated!');
    }

}