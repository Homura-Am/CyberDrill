<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* --- LAYOUT GRID --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr; /* Left Col (Info) vs Right Col (Stats) */
            gap: 2rem;
            margin-top: 2rem;
        }

        /* --- NEW: MODULE GRID --- */
        .module-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* --- CARDS --- */
        .card-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* --- HEADER SECTION --- */
        .profile-header {
            display: flex; align-items: center; gap: 20px;
        }
        .avatar-circle {
            width: 80px; height: 80px;
            flex-shrink: 0; /* <-- ADD THIS LINE to stop the squishing */
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: white; font-weight: bold;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        }

        /* --- STATS GRID --- */
        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;
        }
        .stat-item {
            background: var(--bg-body); border-radius: 12px; padding: 15px; text-align: center;
            border: 1px solid var(--border-color);
        }
        .stat-num { font-size: 1.8rem; font-weight: 800; color: var(--primary-color); }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; }

        /* --- FORMS --- */
        label { display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 0.9rem; }
        select, input {
            width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color);
            background: var(--bg-body); color: var(--text-main); margin-bottom: 15px;
        }

        /* --- MODULE LIST --- */
        .module-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 15px; 
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03); /* Slight background for the boxes */
            transition: 0.2s;
            border: 1px solid var(--border-color);
        }
        .module-item:hover { background: rgba(255,255,255,0.06); }
        
        /* Module Buttons */
        .btn-module {
            padding: 6px 15px;
            font-size: 0.8rem;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-module:hover { opacity: 0.8; }
        .btn-phishing { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6, 182, 212, 0.3); color: #000; }
        .btn-malware { background-color: #b535f6; box-shadow: 0 0 10px rgba(181, 53, 246, 0.3); }
        .btn-spam { background-color: #f59e0b; box-shadow: 0 0 10px rgba(245, 158, 11, 0.3); color: #000; }

        @media (max-width: 900px) { 
            .dashboard-grid { grid-template-columns: 1fr; } 
            .module-grid { grid-template-columns: 1fr; } /* Stacks modules on small screens */
        }
    </style>
</head>
<body>

    @include('partials.navbar')
@php
    // Quick check to see if they unlock the certificate
    $phishScore = \App\Models\SimulationAttempt::where('user_id', Auth::id())->where('module', 'phishing')->max('score') ?? 0;
    $malwScore = \App\Models\SimulationAttempt::where('user_id', Auth::id())->where('module', 'malware')->max('score') ?? 0;
    $spamScore = \App\Models\SimulationAttempt::where('user_id', Auth::id())->where('module', 'spam')->max('score') ?? 0;
    
    $earnedCertificate = ($phishScore >= 80 && $malwScore >= 80 && $spamScore >= 80);
@endphp

<div class="panel" style="text-align: center; margin-top: 2rem;">
    <h3 style="color: var(--primary-color);">🏆 Cyber Security Certification</h3>
    
    @if($earnedCertificate)
        <p style="color: #22c55e; margin-bottom: 15px;">Congratulations! You have passed all modules.</p>
        <a href="{{ route('certificate') }}" class="btn btn-primary" target="_blank">
            View & Print Certificate
        </a>
    @else
        <p style="color: var(--text-muted);">Score 80% or higher on Phishing, Malware, and Spam to unlock your official certificate.</p>
        <div style="opacity: 0.5; margin-top: 15px;">
            <button class="btn" disabled style="background: var(--border-color); color: var(--text-muted); cursor: not-allowed;">
                🔒 Certificate Locked
            </button>
        </div>
    @endif
</div>
    <div class="container">
        
        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 15px; border-radius: 8px; margin-top: 20px; border: 1px solid #22c55e; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <div class="dashboard-grid" style="margin-bottom: 0;">
            
            <div>
                <div class="card-box" style="height: 100%;">
                    <div class="profile-header">
                        <div class="avatar-circle">{{ substr($user->name, 0, 1) }}</div>
                        <div>
                            <h2 style="margin: 0;">{{ $user->name }}</h2>
                            <p style="color: var(--text-muted); margin: 5px 0;">{{ $user->email }}</p>
                            <span style="font-size: 0.8rem; background: rgba(34, 197, 94, 0.2); color: #22c55e; padding: 2px 8px; border-radius: 4px;">Active Recruit</span>
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 25px 0;">

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <label>Select Your Kulliyyah (IIUM)</label>
                        <select name="kulliyyah">
                            <option value="" disabled {{ !$user->kulliyyah ? 'selected' : '' }}>-- Select Kulliyyah --</option>
                            <option value="KICT" {{ $user->kulliyyah == 'KICT' ? 'selected' : '' }}>KICT - ICT</option>
                            <option value="KOE" {{ $user->kulliyyah == 'KOE' ? 'selected' : '' }}>KOE - Engineering</option>
                            <option value="KENMS" {{ $user->kulliyyah == 'KENMS' ? 'selected' : '' }}>KENMS - Economics</option>
                            <option value="IRKHS" {{ $user->kulliyyah == 'IRKHS' ? 'selected' : '' }}>IRKHS - Human Sciences</option>
                            <option value="AIKOL" {{ $user->kulliyyah == 'AIKOL' ? 'selected' : '' }}>AIKOL - Laws</option>
                            <option value="KAED" {{ $user->kulliyyah == 'KAED' ? 'selected' : '' }}>KAED - Architecture</option>
                            <option value="KOS" {{ $user->kulliyyah == 'KOS' ? 'selected' : '' }}>KOS - Science</option>
                            <option value="KOM" {{ $user->kulliyyah == 'KOM' ? 'selected' : '' }}>KOM - Medicine</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
                    </form>
                </div>
            </div>

            <div>
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-num">{{ $stats['score'] ?? 0 }}%</div>
                        <div class="stat-label">Avg. Accuracy</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">{{ $stats['attempted'] ?? 0 }}</div>
                        <div class="stat-label">Total Scenarios</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num" style="color: #22c55e;">{{ $stats['correct'] ?? 0 }}</div>
                        <div class="stat-label">Threats Stopped</div>
                    </div>
                </div>

                <div class="card-box" style="margin-bottom: 0;">
                    <h3 style="margin-bottom: 20px;">Performance Analysis</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        
                        <div style="text-align: center;">
                            <h4 style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 10px;">Overall Success Rate</h4>
                            <div style="height: 200px; position: relative;">
                                <canvas id="doughnutChart"></canvas>
                            </div>
                        </div>

                        <div style="text-align: center;">
                            <h4 style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 10px;">Score History (%)</h4>
                            <div style="height: 200px; position: relative;">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="card-box" style="margin-top: 2rem;">
            <h3 style="margin-bottom: 20px; font-size: 1.1rem;">Module Access & History</h3>
            
            <div class="module-grid">
                
                <div class="module-item" style="border-left: 4px solid #06b6d4;">
                    <div>
                        <strong style="display: block;">Phishing Defense</strong>
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">
                            {{ $phishingStats['attempted'] ?? ($stats['attempted'] ?? 0) }} Scenarios Faced<br>
                            <span style="color: #06b6d4; font-weight: bold;">High Score: {{ $phishingStats['high_score'] ?? 0 }}%</span>
                        </small>
                    </div>
                    <a href="{{ route('phishing.index') }}" class="btn-module btn-phishing">
                        Start Run
                    </a>
                </div>

                <div class="module-item" style="border-left: 4px solid #b535f6;">
                    <div>
                        <strong style="display: block;">Malware Sandbox</strong>
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">
                            {{ $malwareStats['attempted'] ?? 0 }} Artifacts Analyzed<br>
                            <span style="color: #b535f6; font-weight: bold;">High Score: {{ $malwareStats['high_score'] ?? 0 }}%</span>
                        </small>
                    </div>
                    <a href="{{ route('malware') }}" class="btn-module btn-malware">
                        Start Run
                    </a>
                </div>

                <div class="module-item" style="border-left: 4px solid #f59e0b;">
                    <div>
                        <strong style="display: block;">Spam Defense</strong>
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">
                            {{ $spamStats['attempted'] ?? 0 }} Emails Filtered<br>
                            <span style="color: #f59e0b; font-weight: bold;">High Score: {{ $spamStats['high_score'] ?? 0 }}%</span>
                        </small>
                    </div>
                    <a href="{{ route('spam') }}" class="btn-module btn-spam">
                        Start Run
                    </a>
                </div>

            </div>
        </div>
        
    </div>

    <script>
        // --- 1. DOUGHNUT CHART (Lifetime Correct vs Incorrect) ---
        new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Correct Actions', 'Incorrect Actions'],
                datasets: [{
                    data: @json($chartData ?? [0, 0]), 
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8' } } }
            }
        });

        // --- 2. LINE CHART (Score Improvement) ---
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: @json($lineChartLabels ?? []), 
                datasets: [{
                    label: 'Attempt Score (%)',
                    data: @json($lineChartData ?? []), 
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#06b6d4'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { display: false }, grid: { display: false } }, 
                    y: { 
                        min: 0, max: 100,
                        ticks: { color: '#94a3b8', stepSize: 25 }, 
                        grid: { color: 'rgba(255,255,255,0.05)' } 
                    }
                }
            }
        });

        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>