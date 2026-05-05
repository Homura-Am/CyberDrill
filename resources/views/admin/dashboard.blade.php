<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* (Keep your existing styles) */
        .admin-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--bg-card); border: 1px solid var(--border-color); padding: 25px; border-radius: 12px; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .stat-icon { font-size: 2.5rem; background: rgba(255,255,255,0.05); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .stat-info h3 { margin: 0; font-size: 2rem; color: var(--text-main); }
        .stat-info p { margin: 0; color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; }

        .layout-split { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .panel { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); }
        .panel-title { font-size: 1.2rem; font-weight: bold; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        th { text-align: left; padding: 12px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); }
        td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-main); }
        tr:last-child td { border-bottom: none; }
        
        .role-badge { background: rgba(6, 182, 212, 0.2); color: #06b6d4; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
        .score-good { color: #22c55e; font-weight: bold; }
        .score-bad { color: #ef4444; font-weight: bold; }

        /* DELETE BUTTON STYLE */
        .btn-delete {
            background: none; border: none; color: #ef4444; cursor: pointer; padding: 5px;
            transition: transform 0.2s; opacity: 0.7;
        }
        .btn-delete:hover { transform: scale(1.2); opacity: 1; }

        @media (max-width: 900px) { .admin-grid, .layout-split { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container" style="margin-top: 30px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h1 style="margin: 0;">Admin <span style="color: var(--primary-color);">Dashboard</span></h1>
        <span style="color: var(--text-muted);">Manage users and generate reports</span>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.questions.index') }}" class="btn btn-outline" style="display: flex; align-items: center; gap: 10px;">
            ⚙️ Manage Scenarios
        </a>

        <a href="{{ route('admin.export') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 10px;">
            📥 Generate Report
        </a>
    </div>
</div>

        @if(session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #22c55e;">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-grid">
            <div class="stat-card">
                <div class="stat-icon" style="color: #3b82f6;">👥</div>
                <div class="stat-info">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #f59e0b;">⚡</div>
                <div class="stat-info">
                    <h3>{{ $totalAttempts }}</h3>
                    <p>Simulations Run</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #22c55e;">🎯</div>
                <div class="stat-info">
                    <h3>{{ $avgScore }}%</h3>
                    <p>Global Avg Score</p>
                </div>
            </div>
        </div>

        <div class="layout-split">
            
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Registered Users</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Kulliyyah</th>
                            <th>Runs</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="font-weight: bold;">{{ $user->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">Joined {{ $user->created_at->format('M d') }}</div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->kulliyyah ?? '-' }}</td>
                            <td style="text-align: center;">{{ $user->runs_count }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="role-badge" style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6;">Admin</span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.9rem;">User</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    {{ $users->links() }} 
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Manage Recent Activity</span>
                </div>
                
                @if($recentActivity->count() > 0)
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($recentActivity as $activity)
                        <li style="padding: 15px 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">{{ $activity->user->name }}</strong>
                                <span style="font-size: 0.85rem; color: var(--text-muted);">
                                    Completed {{ ucfirst($activity->module) }} - 
                                    <span class="{{ $activity->score >= 80 ? 'score-good' : 'score-bad' }}">{{ $activity->score }}%</span>
                                </span>
                            </div>
                            
                            <form action="{{ route('admin.deleteAttempt', $activity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Delete Record">🗑️</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: var(--text-muted); text-align: center; padding: 20px;">No recent activity.</p>
                @endif
            </div>

        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>