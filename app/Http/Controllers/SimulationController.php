<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SimulationProgress;
use App\Models\SimulationAttempt;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class SimulationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. FETCH SCENARIOS (Robust Fix)
        // We get all questions regardless of module casing
        $dbQuestions = Question::where('module', 'phishing')->get();
        
        $scenarios = [];
        foreach($dbQuestions as $q) {
            // FORCE DECODE OPTIONS: If it's a string, decode it. If it's already an array, keep it.
            $options = is_string($q->options) ? json_decode($q->options, true) : $q->options;

            $scenarios[$q->key] = [
                'title' => $q->title,
                'type' => $q->type,
                'senderName' => $q->sender_name,
                'senderEmail' => $q->sender_email,
                'subject' => $q->subject,
                'body' => $q->body,
                'options' => $options ?? [] // Ensure it's never null
            ];
        }

        // 2. FETCH USER PROGRESS
        $progress = [];
        if ($user) {
            $progress = SimulationProgress::where('user_id', $user->id)
                ->where('module', 'phishing')
                ->pluck('status', 'scenario_id')
                ->toArray();
        }

        // 3. FETCH HISTORY
        $attempts = [];
        if ($user) {
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

    // (Keep update, submitAttempt, reset functions as they were)
    public function update(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);
        $request->validate(['scenario_id' => 'required|string', 'status' => 'required']);
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
        $totalQuestions = Question::where('module', 'phishing')->count();
        if ($totalQuestions == 0) $totalQuestions = 8;

        $correct = $records->where('status', 'correct')->count();
        $score = ($totalQuestions > 0) ? round(($correct / $totalQuestions) * 100) : 0;

        SimulationAttempt::create(['user_id' => $user->id, 'module' => 'phishing', 'score' => $score]);
        SimulationProgress::where('user_id', $user->id)->where('module', 'phishing')->delete();

        return response()->json(['success' => true, 'score' => $score]);
    }
}