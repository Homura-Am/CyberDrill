<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CyberDrill | Security Awareness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* --- GLOBAL BACKGROUND --- */
        

        /* --- WIDGET STYLES --- */
        .user-widget {
            background: rgba(17, 24, 39, 0.7); 
            border: 1px solid rgba(0, 209, 255, 0.2); 
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 3rem; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            margin-top: 2rem;
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
        }

        .user-widget::before {
            content: ''; position: absolute; top: -50px; right: -50px;
            width: 200px; height: 200px; background: #00d1ff;
            opacity: 0.05; border-radius: 50%; filter: blur(50px); pointer-events: none;
        }

        .widget-left { display: flex; align-items: center; gap: 20px; z-index: 1; }
        
        .widget-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #00d1ff, #0055ff);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: white; font-weight: bold;
            box-shadow: 0 0 20px rgba(0, 209, 255, 0.4);
        }

        .widget-info h2 { margin: 0; font-size: 1.8rem; color: #ffffff; }
        .widget-info p { margin: 5px 0 0; color: #9ca3af; font-size: 1rem; }
        .kulliyyah-badge {
            display: inline-block; background: rgba(0, 209, 255, 0.1); 
            padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; margin-top: 8px;
            border: 1px solid rgba(0, 209, 255, 0.3); color: #00d1ff;
        }

        .widget-stats {
            display: flex; gap: 30px; text-align: center; z-index: 1;
            border-left: 1px solid rgba(255,255,255,0.1); padding-left: 30px;
        }
        
        .ws-item h3 { font-size: 2rem; margin: 0; color: #00d1ff; }
        .ws-item span { font-size: 0.85rem; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; }

        @media (max-width: 768px) {
            .user-widget { flex-direction: column; text-align: center; gap: 20px; }
            .widget-left { flex-direction: column; }
            .widget-stats { border-left: none; padding-left: 0; width: 100%; justify-content: space-around; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; }
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
                        <a href="{{ route('profile') }}" class="kulliyyah-badge" style="text-decoration: none;">
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
            <h1 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1rem; position: relative; z-index: 2;">
                CYBER<span style="color: #00d1ff; text-shadow: 0 0 15px rgba(0, 209, 255, 0.5);">DRILL</span>
            </h1>
            <p style="font-size: 1.25rem; color: #9ca3af; max-width: 600px; margin: 0 auto; position: relative; z-index: 2;">
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
    <footer class="module-footer" style="border-top-color: rgba(6, 182, 212, 0.3);">
    <p style="margin-top: 15px; opacity: 0.7;">© {{ date('Y') }} CyberDrill. All rights reserved.</p>
</footer>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Create the Audio object. 
            // I put a temporary high-tech "click" sound URL here so you can test it immediately!
            const hoverSound = new Audio('/sounds/hover.mp3');
            
            // Keep the volume subtle (0.2 = 20%) so it isn't annoying
            hoverSound.volume = 1.0; 

            // 2. Select all the simulation cards
            const cards = document.querySelectorAll('.card');

            // 3. Attach the sound to the 'mouseenter' (hover) event
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    // Rewind to start: Allows rapid hovering to trigger the sound instantly every time
                    hoverSound.currentTime = 0; 
                    
                    // Play the sound (the .catch ignores errors if the browser blocks audio before the user clicks anywhere)
                    hoverSound.play().catch(() => { 
                        // Silently ignore browser autoplay policies
                    });
                });
            });
        });
    </script>
</body>
</html>