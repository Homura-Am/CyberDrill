<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SimulationAttempt;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Security Check (Simple Version)
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Global Stats
        $totalUsers = User::count();
        $totalAttempts = SimulationAttempt::count();
        $avgScore = round(SimulationAttempt::avg('score') ?? 0);
        
        // 3. Recent Activity Feed (Who submitted what recently?)
        $recentActivity = SimulationAttempt::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 4. User List (with attempt counts)
        // We use 'withCount' to efficiently count simulation_attempts for each user
        $users = User::withCount('simulationAttempts as runs_count')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dashboard', compact('totalUsers', 'totalAttempts', 'avgScore', 'recentActivity', 'users'));
    }

    // ... inside AdminController class ...

    // 1. GENERATE REPORT (CSV Export)
    public function export()
    {
        $filename = "cyberdrill-report-" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'User Name', 'Email', 'Kulliyyah', 'Module', 'Score', 'Result'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $attempts = SimulationAttempt::with('user')->orderBy('created_at', 'desc')->get();

            foreach ($attempts as $attempt) {
                // Determine Pass/Fail text based on score
                $result = $attempt->score >= 80 ? 'High Pass' : ($attempt->score >= 50 ? 'Pass' : 'Fail');

                fputcsv($file, [
                    $attempt->created_at->format('Y-m-d H:i:s'),
                    $attempt->user->name,
                    $attempt->user->email,
                    $attempt->user->kulliyyah ?? 'N/A',
                    ucfirst($attempt->module),
                    $attempt->score . '%',
                    $result
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 2. MANAGE SIMULATION (Delete an Attempt)
    public function deleteAttempt($id)
    {
        $attempt = SimulationAttempt::findOrFail($id);
        $attempt->delete();

        return redirect()->back()->with('success', 'Simulation record deleted successfully.');
    }
}