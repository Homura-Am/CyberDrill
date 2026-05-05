<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SimulationProgress;
use App\Models\SimulationAttempt; // <--- Ensure this model is imported
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Fetch ALL Past Attempts (History)
        $attempts = SimulationAttempt::where('user_id', $user->id)
            ->where('module', 'phishing')
            ->orderBy('created_at', 'asc') // Oldest first for line graph
            ->get();

        // 2. Fetch Current Active Run (Progress)
        $currentProgress = SimulationProgress::where('user_id', $user->id)
            ->where('module', 'phishing')
            ->get();

        // --- CALCULATE LIFETIME STATS ---
        $scenariosPerRun = 8; // Constant based on your simulation
        
        // A. From Past Attempts
        $pastRunsCount = $attempts->count();
        $pastScenariosAttempted = $pastRunsCount * $scenariosPerRun;
        
        // Calculate total correct from history (Score % -> Count)
        // Formula: (Score / 100) * 8
        $pastCorrect = $attempts->sum(function($attempt) use ($scenariosPerRun) {
            return round(($attempt->score / 100) * $scenariosPerRun);
        });
        
        $pastIncorrect = $pastScenariosAttempted - $pastCorrect;

        // B. From Current Active Run
        $currentAttempted = $currentProgress->count();
        $currentCorrect = $currentProgress->where('status', 'correct')->count();
        $currentIncorrect = $currentProgress->where('status', 'incorrect')->count();

        // C. GRAND TOTALS
        $totalScenariosSeen = $pastScenariosAttempted + $currentAttempted;
        $totalThreatsStopped = $pastCorrect + $currentCorrect;
        $totalIncorrect = $pastIncorrect + $currentIncorrect;

        // Calculate Average Accuracy across all attempts
        $averageAccuracy = $attempts->count() > 0 
            ? round($attempts->avg('score')) 
            : ($currentAttempted > 0 ? round(($currentCorrect/$currentAttempted)*100) : 0);

        // --- PREPARE CHART DATA ---

        // 1. Doughnut Chart (Lifetime Correct vs Incorrect)
        // We calculate "Total Pending" as scenarios required to reach the next "Level" or just 0 for history
        // For profile, let's just show Correct vs Incorrect ratio
        $doughnutData = [$totalThreatsStopped, $totalIncorrect];

        // 2. Line Chart (Progress Over Time)
        // We map the attempts to dates
        $dates = [];
        $scores = [];

        foreach ($attempts as $attempt) {
            $dates[] = $attempt->created_at->format('M d H:i');
            $scores[] = $attempt->score;
        }

        return view('profile', [
            'user' => $user,
            'stats' => [
                'score' => $averageAccuracy,
                'attempted' => $totalScenariosSeen, // Total scenarios ever faced
                'correct' => $totalThreatsStopped,
                'total_runs' => $pastRunsCount
            ],
            'chartData' => $doughnutData, // [Correct, Incorrect]
            'lineChartLabels' => $dates,
            'lineChartData' => $scores
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(['kulliyyah' => 'required|string|max:255']);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['kulliyyah' => $request->kulliyyah]);
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}