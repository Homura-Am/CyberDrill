<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SimulationAttempt; 

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Fetch ALL attempts across all modules, ordered by oldest to newest (for the line chart)
        $allAttempts = SimulationAttempt::where('user_id', $user->id)->orderBy('created_at', 'asc')->get();

        // 2. Break down the stats by module type
        // Note: This assumes you added a 'module' or 'type' column to your simulation_attempts table!
        // If your column is named differently (e.g., 'module_type'), change it below.
        $phishingAttempts = $allAttempts->where('module', 'phishing');
        $malwareAttempts  = $allAttempts->where('module', 'malware');
        $spamAttempts     = $allAttempts->where('module', 'spam');

        $phishingStats = ['attempted' => $phishingAttempts->count()];
        $malwareStats  = ['attempted' => $malwareAttempts->count()];
        $spamStats     = ['attempted' => $spamAttempts->count()];

        // 3. Calculate Overall Stats (Top right numbers)
        $totalAttempts = $allAttempts->count();
        $avgScore = $totalAttempts > 0 ? round($allAttempts->avg('score')) : 0;

        $stats = [
            'attempted' => $totalAttempts,
            'score'     => $avgScore,
            // Let's count "Threats Stopped" as any run where they scored 80% or higher
            'correct'   => $allAttempts->where('score', '>=', 80)->count() 
        ];

        // 4. Generate Chart Data (Combines all modules based on Average Score)
        $chartData = [$avgScore, 100 - $avgScore];

        $lineChartLabels = $allAttempts->pluck('created_at')->map(function($date) {
            return $date->format('M d, H:i');
        })->toArray();
        
        $lineChartData = $allAttempts->pluck('score')->toArray();

        // 5. Send it all to the view
        return view('profile', compact(
            'user', 
            'stats', 
            'phishingStats', 
            'malwareStats', 
            'spamStats', 
            'chartData', 
            'lineChartLabels', 
            'lineChartData'
        ));
    }
    
    // ... keep your update() method below ...


    public function update(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        
        $request->validate([
            'scenario_id' => 'required|string', 
            'status' => 'required'
        ]);
        
        SimulationProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'module' => 'phishing', 'scenario_id' => $request->scenario_id],
            ['status' => $request->status]
        );
        
        return response()->json(['success' => true]);
    }

    public function submitAttempt()
    {
        $user = Auth::user();
        $records = SimulationProgress::where('user_id', $user->id)->where('module', 'phishing')->get();
        
        // 3. Count using the new model instead of the old Question model
        $totalQuestions = PhishingScenario::count();
        if ($totalQuestions == 0) $totalQuestions = 4; // We have 4 seeded right now

        $correct = $records->where('status', 'correct')->count();
        $score = ($totalQuestions > 0) ? round(($correct / $totalQuestions) * 100) : 0;

        SimulationAttempt::create(['user_id' => $user->id, 'module' => 'phishing', 'score' => $score]);
        SimulationProgress::where('user_id', $user->id)->where('module', 'phishing')->delete();

        return response()->json(['success' => true, 'score' => $score]);
    }


    // --- MALWARE MODULE METHODS (Untouched) --- //
    
    public function showMalware()
    {
        $scenarios = \App\Models\MalwareScenario::all();

        $attempts = \App\Models\SimulationAttempt::where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('module', 'malware')
                    ->latest()
                    ->take(5)
                    ->get();

        $progress = \App\Models\SimulationProgress::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('module', 'malware')
            ->pluck('status', 'scenario_id')
            ->toArray();

        return view('malware', [
            'scenarios' => $scenarios,
            'savedProgress' => $progress,
            'attempts' => $attempts 
        ]);
    }

    public function submitMalwareAttempt(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        
        $totalScenarios = \App\Models\MalwareScenario::count();
        $correctAnswers = \App\Models\SimulationProgress::where('user_id', $userId)
                            ->where('module', 'malware')
                            ->where('status', 'correct')
                            ->count();
        
        $score = ($totalScenarios > 0) ? round(($correctAnswers / $totalScenarios) * 100) : 0;

        \App\Models\SimulationAttempt::create([
            'user_id' => $userId,
            'module' => 'malware',
            'score' => $score,
        ]);

        \App\Models\SimulationProgress::where('user_id', $userId)
            ->where('module', 'malware')
            ->delete();

        return response()->json(['success' => true, 'score' => $score]);
    }

    public function updateMalware(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        \App\Models\SimulationProgress::updateOrCreate(
            [
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'module' => 'malware', 
                'scenario_id' => $request->question_id, 
            ],
            [
                'status' => $request->status,
            ]
        );

        return response()->json(['success' => true]);
    }


    // --- SPAM MODULE METHODS (Untouched) --- //

    public function showSpam()
    {
        $scenarios = \App\Models\Question::where('module', 'spam')->get();
        $savedProgress = session()->get('spam_progress', []);
        $attempts = \App\Models\SimulationAttempt::where('user_id', auth()->id())
                        ->where('module', 'spam')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

        return view('spam', compact('scenarios', 'savedProgress', 'attempts'));
    }

    public function updateSpam(Request $request)
    {
        $progress = session()->get('spam_progress', []);
        $progress[$request->question_id] = $request->status;
        session()->put('spam_progress', $progress);

        return response()->json(['success' => true]);
    }

    public function submitSpam(Request $request)
    {
        $scenarios = \App\Models\Question::where('module', 'spam')->get();
        $progress = session()->get('spam_progress', []);
        
        $correct = 0;
        foreach ($progress as $id => $status) {
            if ($status === 'correct') $correct++;
        }

        $score = count($scenarios) > 0 ? round(($correct / count($scenarios)) * 100) : 0;

        \App\Models\SimulationAttempt::create([
            'user_id' => auth()->id(),
            'module' => 'spam',
            'score' => $score
        ]);

        session()->forget('spam_progress');

        return response()->json(['score' => $score]);
    }
}