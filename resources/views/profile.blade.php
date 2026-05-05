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
            padding: 15px; border-bottom: 1px solid var(--border-color);
            transition: 0.2s;
        }
        .module-item:last-child { border-bottom: none; }
        .module-item:hover { background: rgba(255,255,255,0.02); }
        .progress-bar-track {
            width: 100px; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;
        }
        .progress-bar-fill { height: 100%; background: var(--primary-color); }

        @media (max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container">
        
        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 15px; border-radius: 8px; margin-top: 20px; border: 1px solid #22c55e; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <div class="dashboard-grid">
            
            <div>
                <div class="card-box">
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

                <div class="card-box">
                    <h3 style="margin-bottom: 15px; font-size: 1.1rem;">Module History</h3>
                    
                    <div class="module-item" style="border-left: 3px solid #06b6d4; padding-left: 15px;">
                        <div>
                            <strong style="display: block;">Phishing Defense</strong>
                            <small style="color: var(--text-muted);">
                                {{ $stats['attempted'] }} Scenarios Faced across {{ $stats['total_runs'] ?? 1 }} Runs
                            </small>
                        </div>
                        
                        <a href="{{ route('phishing.index') }}" class="btn btn-primary" style="padding: 6px 15px; font-size: 0.8rem;">
                            Start New Run
                        </a>
                    </div>

                    <div class="module-item" style="border-left: 3px solid #8b5cf6; padding-left: 15px; opacity: 0.6;">
                        <div>
                            <strong style="display: block;">Malware Awareness</strong>
                            <small style="color: var(--text-muted);">Not Started</small>
                        </div>
                        <button class="btn btn-outline" disabled style="padding: 4px 10px; font-size: 0.8rem;">Locked</button>
                    </div>

                    <div class="module-item" style="border-left: 3px solid #f59e0b; padding-left: 15px; opacity: 0.6;">
                        <div>
                            <strong style="display: block;">Spam Defense</strong>
                            <small style="color: var(--text-muted);">Not Started</small>
                        </div>
                        <button class="btn btn-outline" disabled style="padding: 4px 10px; font-size: 0.8rem;">Locked</button>
                    </div>
                </div>
            </div>

            <div>
                <div class="stats-row">
                    <div class="stat-item">
                        <div class="stat-num">{{ $stats['score'] }}%</div>
                        <div class="stat-label">Avg. Accuracy</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">{{ $stats['attempted'] }}</div>
                        <div class="stat-label">Total Scenarios</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num" style="color: #22c55e;">{{ $stats['correct'] }}</div>
                        <div class="stat-label">Threats Stopped</div>
                    </div>
                </div>

                <div class="card-box">
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

<script>
        // --- 1. DOUGHNUT CHART (Lifetime Correct vs Incorrect) ---
        new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Correct Actions', 'Incorrect Actions'],
                datasets: [{
                    data: @json($chartData), // [Total Correct, Total Incorrect]
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
                labels: @json($lineChartLabels), // Dates
                datasets: [{
                    label: 'Attempt Score (%)',
                    data: @json($lineChartData), // Scores (e.g. 80, 100, 50)
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
                    x: { ticks: { display: false }, grid: { display: false } }, // Hide messy dates
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