<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimulationProgress;
use App\Models\SimulationAttempt;
use App\Models\Question;
use App\Models\PhishingScenario; // <-- 1. Add your new model here
use Illuminate\Support\Facades\Auth;

class SimulationController extends Controller
{
    // --- PHISHING MODULE METHODS --- //

    public function index()
    {
        $user = Auth::user();
        
        // 2. Fetch directly from the new PhishingScenario model and key it by 'key'
        // This completely replaces that big foreach loop you had before!
        $scenarios = PhishingScenario::all()->keyBy('key');
        
        $progress = [];
        $attempts = [];

        if ($user) {
            $progress = SimulationProgress::where('user_id', $user->id)
                ->where('module', 'phishing')
                ->pluck('status', 'scenario_id')
                ->toArray();

            $attempts = SimulationAttempt::where('user_id', $user->id)
                ->where('module', 'phishing')
                ->latest()
                ->get();
        }

        return view('phishing', [
            'scenarios' => $scenarios,
            'savedProgress' => $progress,
            'attempts' => $attempts
        ]);
    }

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