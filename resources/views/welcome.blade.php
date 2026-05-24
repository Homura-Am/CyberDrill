<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CyberDrill | Security Awareness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* --- WIDGET STYLES --- */
        .user-widget {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 3rem; /* Space below widget */
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            margin-top: 2rem;
        }

        .user-widget::before {
            content: ''; position: absolute; top: -50px; right: -50px;
            width: 200px; height: 200px; background: var(--primary-color);
            opacity: 0.05; border-radius: 50%; blur: 50px; pointer-events: none;
        }

        .widget-left { display: flex; align-items: center; gap: 20px; z-index: 1; }
        
        .widget-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: white; font-weight: bold;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        }

        .widget-info h2 { margin: 0; font-size: 1.8rem; color: var(--text-main); }
        .widget-info p { margin: 5px 0 0; color: var(--text-muted); font-size: 1rem; }
        .kulliyyah-badge {
            display: inline-block; background: rgba(255, 255, 255, 0.1); 
            padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; margin-top: 8px;
            border: 1px solid rgba(255,255,255,0.1); color: var(--text-muted);
        }

        .widget-stats {
            display: flex; gap: 30px; text-align: center; z-index: 1;
            border-left: 1px solid var(--border-color); padding-left: 30px;
        }
        
        .ws-item h3 { font-size: 2rem; margin: 0; color: var(--primary-color); }
        .ws-item span { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        @media (max-width: 768px) {
            .user-widget { flex-direction: column; text-align: center; gap: 20px; }
            .widget-left { flex-direction: column; }
            .widget-stats { border-left: none; padding-left: 0; width: 100%; justify-content: space-around; border-top: 1px solid var(--border-color); padding-top: 20px; }
        }
    </style>
</head>
<body class="antialiased">

    @include('partials.navbar')

    <div class="container">
        
        @auth
        <div class="user-widget">
            <div class="widget-left">
                <div class="widget-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="widget-info">
                    <h2>Welcome back, {{ explode(' ', $user->name)[0] }}!</h2>
                    <p>{{ $user->email }}</p>
                    @if($user->kulliyyah)
                        <span class="kulliyyah-badge">🏛️ {{ $user->kulliyyah }}</span>
                    @else
                        <a href="{{ route('profile') }}" class="kulliyyah-badge" style="text-decoration: none; color: var(--primary-color); border-color: var(--primary-color);">
                            + Add Kulliyyah
                        </a>
                    @endif
                </div>
            </div>

            <div class="widget-stats">
                <div class="ws-item">
                    <h3>{{ $stats['avg_score'] }}%</h3>
                    <span>Avg Score</span>
                </div>
                <div class="ws-item">
                    <h3>{{ $stats['total_runs'] }}</h3>
                    <span>Simulations</span>
                </div>
                <div class="ws-item">
                    <h3 style="font-size: 1.2rem; line-height: 2.4rem;">{{ $stats['last_active'] }}</h3>
                    <span>Last Active</span>
                </div>
            </div>
        </div>
        @endauth

        <div style="text-align: center; margin: 2rem 0 4rem 0;">
            <h1 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1rem;">
                CYBER<span style="color: var(--primary-color);">DRILL</span>
            </h1>
            <p style="font-size: 1.25rem; color: var(--text-muted); max-width: 600px; margin: 0 auto;">
                Interactive cybersecurity training simulations. Get to know the cyberthreat around you and test your knowledge.
            </p>
        </div>

        <div class="grid">
            <div class="card card-phishing">
                <div class="card-icon">🎣</div>
                <h2 class="card-title">Phishing</h2>
                <p class="card-desc">Master social engineering defense. Spot fake emails and malicious links.</p>
                <div class="card-actions">
                    <a href="{{ route('phishing.index') }}" class="btn btn-primary btn-block">Start Simulation</a>
                    <a href="{{ route('learn.phishing') }}" class="learn-link">Learn More &rarr;</a>
                </div>
            </div>

            <div class="card card-malware">
                <div class="card-icon">🦠</div>
                <h2 class="card-title">Malware</h2>
                <p class="card-desc">Understand viruses and ransomware. Learn safe file handling.</p>
                <div class="card-actions">
                    <a href="{{ route('malware') }}" class="btn btn-primary btn-block">Start Simulation</a>
                    <a href="{{ route('learn.malware') }}" class="learn-link">Learn More &rarr;</a>
                </div>
            </div>

            <div class="card card-spam">
                <div class="card-icon">🗑️</div>
                <h2 class="card-title">Spam Defense</h2>
                <p class="card-desc">Reclaim your inbox. Learn to filter junk mail effectively.</p>
                <div class="card-actions">
                    <a href="{{ route('spam') }}" class="btn btn-primary btn-block">Start Simulation</a>
                    <a href="{{ route('learn.spam') }}" class="learn-link">Learn More &rarr;</a>
                </div>
            </div>
        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>

</body>
</html>