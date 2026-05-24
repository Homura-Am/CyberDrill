<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spam Triage | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>

    <style>
        :root {
            /* Spam Module Accent Color - Amber */
            --module-accent: #f59e0b; 
            --module-accent-glow: rgba(245, 158, 11, 0.2);
            --safe-color: #22c55e;
            --danger-color: #ef4444;
            --danger-glow: rgba(239, 68, 68, 0.4);
        }

        .dashboard-grid { 
            display: grid; 
            grid-template-columns: 300px 1fr; 
            gap: 25px; 
            margin-top: 30px;
        }

        .panel { 
            background: var(--bg-card); 
            padding: 25px; 
            border-radius: 12px; 
            border: 1px solid var(--border-color); 
        }

        /* --- IMMERSIVE FLASH OVERLAY --- */
        #flash-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none; /* Lets clicks pass through to the buttons */
            z-index: 9999;
            opacity: 0; /* Hidden by default */
        }

        /* Resident Evil Damage Effect */
        .flash-danger {
            background: radial-gradient(circle, transparent 40%, rgba(239, 68, 68, 0.6) 100%);
            box-shadow: inset 0 0 150px rgba(239, 68, 68, 0.8);
            animation: flash-fade 0.6s ease-out forwards;
        }

        /* Pleasant Success Pulse */
        .flash-success {
            background: radial-gradient(circle, transparent 50%, rgba(34, 197, 94, 0.2) 100%);
            box-shadow: inset 0 0 80px rgba(34, 197, 94, 0.4);
            animation: flash-fade 0.5s ease-out forwards;
        }

        @keyframes flash-fade {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* --- SCREEN SHAKE ANIMATION --- */
        @keyframes damage-shake {
            0% { transform: translate(1px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(3px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            60% { transform: translate(-3px, 1px) rotate(0deg); }
            70% { transform: translate(3px, 1px) rotate(-1deg); }
            80% { transform: translate(-1px, -1px) rotate(1deg); }
            90% { transform: translate(1px, 2px) rotate(0deg); }
            100% { transform: translate(1px, -2px) rotate(-1deg); }
        }

        .shake-active {
            animation: damage-shake 0.4s cubic-bezier(.36,.07,.19,.97) both;
        }

        /* --- LAYOUT: EMAIL + STOPWATCH --- */
        .triage-flex-container {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        /* --- STOPWATCH STYLES --- */
        .stopwatch-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            min-width: 110px; 
        }
        
        .stopwatch {
            position: relative;
            width: 100px;
            height: 100px;
            border: 4px solid var(--module-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.4);
            box-shadow: 0 0 15px var(--module-accent-glow), inset 0 0 15px rgba(0,0,0,0.5);
            transition: all 0.3s ease;
        }

        .stopwatch::before {
            content: ''; position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            width: 18px; height: 8px; background: var(--module-accent);
            border-radius: 4px 4px 0 0; transition: all 0.3s ease;
        }

        .stopwatch::after {
            content: ''; position: absolute; top: 2px; right: 6px; width: 12px; height: 8px;
            background: var(--module-accent); transform: rotate(45deg);
            border-radius: 2px; transition: all 0.3s ease;
        }

        .timer-text {
            font-size: 1.6rem; font-family: monospace; font-weight: bold;
            color: var(--module-accent); text-shadow: 0 0 8px var(--module-accent-glow);
            transition: color 0.3s ease;
        }

        .stopwatch.critical {
            border-color: var(--danger-color);
            box-shadow: 0 0 20px var(--danger-glow), inset 0 0 15px rgba(0,0,0,0.5);
            animation: pulse-danger 1s infinite alternate;
        }
        .stopwatch.critical::before, .stopwatch.critical::after { background: var(--danger-color); }
        .stopwatch.critical .timer-text { color: var(--danger-color); text-shadow: 0 0 8px var(--danger-glow); }

        @keyframes pulse-danger {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        /* --- ACTIVE EMAIL DISPLAY --- */
        .email-reader {
            flex-grow: 1; 
            background: #1e1e2f; 
            border-left: 4px solid var(--module-accent);
            border-radius: 8px; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            min-height: 350px;
            display: flex;
            flex-direction: column;
        }
        .email-header { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2); }
        .email-body { padding: 20px; color: #d1d5db; font-family: monospace; font-size: 1rem; line-height: 1.6; white-space: pre-wrap; flex-grow: 1; overflow-y: auto; }

        /* --- TRIAGE CONTROLS --- */
        .triage-controls { display: flex; gap: 15px; margin-top: 25px; }
        .btn-triage { 
            flex: 1; background: transparent; padding: 15px; border-radius: 6px; 
            cursor: pointer; font-weight: bold; font-size: 1.1rem; transition: all 0.2s; 
            text-transform: uppercase; letter-spacing: 1px; 
        }
        .btn-junk { border: 2px solid var(--danger-color); color: var(--danger-color); }
        .btn-junk:hover { background: var(--danger-color); color: white; box-shadow: 0 0 15px var(--danger-glow); }
        .btn-inbox { border: 2px solid var(--safe-color); color: var(--safe-color); }
        .btn-inbox:hover { background: var(--safe-color); color: white; box-shadow: 0 0 15px rgba(34, 197, 94, 0.4); }

        /* --- STATS SIDEBAR --- */
        .stat-block { background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .score-display { font-size: 3.5rem; font-weight: bold; text-align: center; margin: 20px 0; color: var(--module-accent); text-shadow: 0 0 15px var(--module-accent-glow); }
        
        #feedback-screen, #completion-screen, #active-email-container, #countdown-screen { display: none; }
        #ready-screen { display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; min-height: 450px; }

        @media (max-width: 1024px) { 
            .dashboard-grid { grid-template-columns: 1fr; } 
            .triage-flex-container { flex-direction: column-reverse; align-items: center; }
        }

        /* --- NEW: SHIFT COMPLETE SCREEN --- */
        #shift-complete-screen {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 15, 5, 0.95);
            z-index: 10000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .success-title {
            color: var(--safe-color);
            font-size: 5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(34, 197, 94, 0.6);
            animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes pop-in {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        #health-score {
        transition: all 0.4s ease;
        font-size: 3.5rem; /* Larger for better visibility */
        font-weight: bold;
        text-align: center;
        margin: 20px 0;
}

    </style>
</head>
<body id="simulation-body">

    <div id="flash-overlay"></div>

    @include('partials.navbar')

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        <header style="margin-top: 40px;">
            <h1 style="color: var(--module-accent); font-size: 2.2rem; letter-spacing: 1px;">MODULE 03 // RAPID SPAM TRIAGE</h1>
            <p style="color: var(--text-muted);">Warning: Incoming threats detected. Filter mail before the timer expires to maintain integrity.</p>
        </header>

        <div class="dashboard-grid">
            
            <div class="panel" style="display: flex; flex-direction: column;">
                <h3 style="margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">System Health</h3>
                
                <div class="score-display" id="health-score">100%</div>

                <div class="stat-block">
                    <span style="color: var(--text-muted);">Queue:</span>
                    <strong id="queue-count">0 / {{ count($scenarios ?? []) }}</strong>
                </div>
                <div class="stat-block">
                    <span style="color: var(--safe-color);">Blocked:</span>
                    <strong id="correct-count">0</strong>
                </div>
                <div class="stat-block">
                    <span style="color: var(--danger-color);">Breaches:</span>
                    <strong id="error-count">0</strong>
                </div>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
    <h4 style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; text-align: center;">Last 3 Shifts</h4>
    
    <div style="display: flex; justify-content: center; gap: 10px;">
        @forelse($attempts->take(3) as $attempt)
            @php
                $score = $attempt->score;
                // Calculate colors dynamically based on score thresholds
                if ($score >= 80) {
                    $color = 'var(--safe-color)';
                    $bg = 'rgba(34, 197, 94, 0.1)';
                } elseif ($score >= 50) {
                    $color = 'var(--module-accent)';
                    $bg = 'rgba(245, 158, 11, 0.1)';
                } else {
                    $color = 'var(--danger-color)';
                    $bg = 'rgba(239, 68, 68, 0.1)';
                }
            @endphp
            
            <div title="{{ $attempt->created_at->format('M d, H:i') }}" style="padding: 6px 12px; border-radius: 4px; background: {{ $bg }}; color: {{ $color }}; font-weight: bold; font-size: 0.95rem; border: 1px solid {{ $color }}; box-shadow: 0 0 8px {{ $bg }}; cursor: help;">
                {{ $score }}%
            </div>
        @empty
            <span style="color: var(--text-muted); font-size: 0.85rem;">No previous shifts</span>
        @endforelse
    </div>
    </div>

                
            </div>

            <div class="panel" id="triage-workspace">
                
                <div id="ready-screen">
                    <div style="font-size: 5rem; margin-bottom: 15px;">⏱️</div>
                    <h2 style="color: var(--module-accent); font-size: 2.5rem; margin-bottom: 10px;">READY FOR TRIAGE?</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 400px; margin-bottom: 30px;">
                        You have <strong>15 seconds</strong> per email to decide if it is <em>Safe</em> or <em>Junk</em>. If the stopwatch hits zero, a breach will occur.
                    </p>
                    <button class="btn-triage" style="background: var(--module-accent); color: #000; border: none; padding: 15px 50px; max-width: 300px;" onclick="startSimulation()">
                        BEGIN SIMULATION
                    </button>
                </div>

                <div id="countdown-screen" style="flex-direction: column; justify-content: center; align-items: center; text-align: center; min-height: 450px;">
                    <h1 id="countdown-number" style="font-size: 8rem; color: var(--module-accent); text-shadow: 0 0 20px var(--module-accent-glow); margin: 0;">3</h1>
                </div>

                <div id="active-email-container">
                    <div class="triage-flex-container">
                        <div class="email-reader">
                            <div class="email-header">
                                <div style="margin-bottom: 8px;"><strong style="color: #a0aec0; display: inline-block; width: 60px;">From:</strong> <span id="em-sender"></span></div>
                                <div><strong style="color: #a0aec0; display: inline-block; width: 60px;">Subj:</strong> <span id="em-subject" style="color: var(--module-accent); font-weight: bold;"></span></div>
                            </div>
                            <div class="email-body" id="em-body"></div>
                        </div>

                        <div class="stopwatch-wrapper">
                            <div class="stopwatch" id="stopwatch-ui">
                                <span class="timer-text" id="countdown-clock">15.0s</span>
                            </div>
                            <small style="color: var(--text-muted); font-weight: bold; letter-spacing: 1px;">TIME LEFT</small>
                        </div>
                    </div>

                    <div class="triage-controls">
                        <button class="btn-triage btn-junk" onclick="processEmail('junk')">🗑️ FLAG AS JUNK</button>
                        <button class="btn-triage btn-inbox" onclick="processEmail('inbox')">📥 ALLOW TO INBOX</button>
                    </div>
                </div>

                <div id="feedback-screen" style="text-align: center; padding: 40px 20px;">
                    <div id="fb-icon" style="font-size: 5rem; margin-bottom: 10px;">✅</div>
                    <h2 id="fb-title" style="font-size: 2rem; margin-bottom: 15px;">THREAT NEUTRALIZED</h2>
                    <p id="fb-message" style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.6; max-width: 600px; margin: 0 auto 30px auto;"></p>
                    <button class="btn-triage" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); max-width: 300px; margin: 0 auto;" onclick="loadNextEmail()">LOAD NEXT EMAIL ➔</button>
                </div>
                
                <div id="completion-screen" style="text-align: center; padding: 60px 20px;">
                    <h1 style="color: var(--module-accent); font-size: 3rem; margin-bottom: 20px;">QUEUE EMPTY</h1>
                    <p style="color: var(--text-muted); font-size: 1.2rem;">You have processed all incoming mail for this session.</p>
                    <p style="margin-top: 20px;">Review your final System Health on the left and click <strong>End Shift</strong> to record your score.</p>
                    
                    <button onclick="submitShift()" class="btn-triage" style="margin-top: 40px; background: var(--module-accent); color: #000; border: none; padding: 15px 50px; font-size: 1.3rem; max-width: 400px; width: 100%;">
                        ✅ END SHIFT & SUBMIT SCORE
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div id="shift-complete-screen">
        <div id="shift-emoji" style="font-size: 6rem; margin-bottom: 10px;">🏆</div>
        <h1 class="success-title">SHIFT COMPLETE</h1>
        <p id="shift-message" style="color: #fff; font-size: 1.5rem; max-width: 600px; margin-bottom: 10px; line-height: 1.6;">
            Excellent work protecting the network.
        </p>
        <div style="font-size: 1.5rem; color: #a0aec0; margin-bottom: 40px;">
            Final System Health: <strong id="final-score-display" style="font-size: 2.5rem; display: block; margin-top: 10px;">100%</strong>
        </div>
        <button class="btn-triage btn-inbox"style="all: unset;background: var(--safe-color); : white; padding: 8px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 0.9rem; height: 36px;  width: fit-content; 
                display: inline-flex; align-items: center; justify-content: center;letter-spacing: 0.5px;box-shadow: 0 4px 6px rgba(0,0,0,0.2);"onclick="window.location.reload()">RETURN TO SIMULATION
        </button>
    </div>
    

    <script>
    const scenarios = @json($scenarios ?? []);
    
    // NEW: Load past scores from Laravel, or use this dummy data [100, 45, 80] to test the UI
    const pastScores = @json($pastScores ?? [100, 45, 80]);    let currentIndex = 0;
    let timerInterval;
    let timeLeft = 15.0;
    const TIME_LIMIT = 15.0; 
    
    let stats = {
        processed: 0,
        correct: 0,
        errors: 0,
        health: 100
    };

    const sfx = {
        openPage: new Audio('/sounds/open_page.mp3'),
        countdown: new Audio('/sounds/countdown.mp3'),
        timerTick: new Audio('/sounds/timer_tick.m4a'),
        correct: new Audio('/sounds/correct.mp3'),
        wrong: new Audio('/sounds/wrong.mp3'),
        healthGone: new Audio('/sounds/health_gone.mp3'),
        shiftEnd: new Audio('/sounds/shift_end.mp3')
    };

    window.onload = () => {
        sfx.openPage.play().catch(e => console.log('Audio autoplay prevented'));
        if(scenarios.length === 0) {
            document.getElementById('ready-screen').innerHTML = "<p>No scenarios found.</p>";
        }
        updateUI(); // This now works!
    };

    // --- CORE UI UPDATER (Sidebar & Colors) ---
    function updateUI() {
        const healthScore = document.getElementById('health-score');
        
        // 1. Update text
        healthScore.innerText = stats.health + "%";
        document.getElementById('correct-count').innerText = stats.correct;
        document.getElementById('error-count').innerText = stats.errors;
        document.getElementById('queue-count').innerText = `${currentIndex} / ${scenarios.length}`;

        // 2. Dynamic Sidebar Coloring (Green -> Yellow -> Red)
        if (stats.health >= 80) {
            healthScore.style.color = 'var(--safe-color)';
            healthScore.style.textShadow = '0 0 15px rgba(34, 197, 94, 0.4)';
        } else if (stats.health >= 50) {
            healthScore.style.color = 'var(--module-accent)';
            healthScore.style.textShadow = '0 0 15px rgba(245, 158, 11, 0.4)';
        } else {
            healthScore.style.color = 'var(--danger-color)';
            healthScore.style.textShadow = '0 0 15px rgba(239, 68, 68, 0.6)';
        }
    }

    function startSimulation() {
        document.getElementById('ready-screen').style.display = 'none';
        const countdownScreen = document.getElementById('countdown-screen');
        const countdownNumber = document.getElementById('countdown-number');
        countdownScreen.style.display = 'flex';
        
        let count = 3;
        countdownNumber.innerText = count;
        sfx.countdown.play();

        const countdownInterval = setInterval(() => {
            count--;
            if (count > 0) { countdownNumber.innerText = count; } 
            else if (count === 0) { countdownNumber.innerText = "GO!"; } 
            else {
                clearInterval(countdownInterval);
                countdownScreen.style.display = 'none';
                renderEmail(); 
            }
        }, 1000);
    }

    function startTimer() {
        timeLeft = TIME_LIMIT;
        const clock = document.getElementById('countdown-clock');
        const stopwatchUI = document.getElementById('stopwatch-ui');
        
        clock.innerText = "15.0s";
        stopwatchUI.classList.remove('critical'); 
        clearInterval(timerInterval); 
        
        sfx.timerTick.currentTime = 0;
        sfx.timerTick.play();
        
        timerInterval = setInterval(() => {
            timeLeft = Math.round((timeLeft - 0.1) * 10) / 10;
            clock.innerText = timeLeft.toFixed(1) + "s";

            if(timeLeft <= 5.0) stopwatchUI.classList.add('critical');

            if(timeLeft <= 0) {
                clearInterval(timerInterval);
                sfx.timerTick.pause();
                processTimeout(); 
            }
        }, 100);
    }

    function renderEmail() {
        const email = scenarios[currentIndex];
        document.getElementById('em-sender').innerText = `${email.sender_name} <${email.sender_email}>`;
        document.getElementById('em-subject').innerText = email.subject;
        document.getElementById('em-body').innerText = email.body;
        document.getElementById('active-email-container').style.display = 'block';
        document.getElementById('feedback-screen').style.display = 'none';
        startTimer(); 
    }

    function processEmail(decision) {
        clearInterval(timerInterval);
        sfx.timerTick.pause();
        const email = scenarios[currentIndex];
        const options = JSON.parse(email.options);
        const correctOption = options.find(opt => opt.result === 'correct');
        const isCorrect = (decision === 'junk' && correctOption.text.includes('Junk')) || 
                          (decision === 'inbox' && correctOption.text.includes('Inbox'));
        const feedbackObj = options.find(o => (decision === 'junk' ? o.text.includes('Junk') : o.text.includes('Inbox')));
        handleResult(isCorrect, feedbackObj.feedback, email.id);
    }

    function processTimeout() {
        handleResult(false, "TIME EXPIRED! Threat bypassed your filter.", scenarios[currentIndex].id);
    }

    function triggerFlash(isCorrect) {
        const overlay = document.getElementById('flash-overlay');
        const body = document.getElementById('simulation-body');
        overlay.className = '';
        body.classList.remove('shake-active');
        void overlay.offsetWidth; 
        if(isCorrect) overlay.classList.add('flash-success');
        else {
            overlay.classList.add('flash-danger');
            body.classList.add('shake-active');
        }
    }

    function handleResult(isCorrect, message, questionId) {
        stats.processed++;
        triggerFlash(isCorrect);
        
        if (isCorrect) {
            stats.correct++;
            sfx.correct.play();
            showFeedback(true, message);
        } else {
            stats.errors++;
            stats.health = Math.max(0, stats.health - 15);
            sfx.wrong.play();
            
            if(stats.health === 0) {
                sfx.healthGone.play();
                updateUI();
                // Skip alert - go straight to result screen
                setTimeout(() => { submitShift(true); }, 500);
                return;
            }
            showFeedback(false, message);
        }
        updateUI();

        fetch('/spam/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ question_id: questionId, status: isCorrect ? 'correct' : 'incorrect' })
        });
    }

    function showFeedback(isCorrect, message) {
        document.getElementById('active-email-container').style.display = 'none';
        const fbScreen = document.getElementById('feedback-screen');
        fbScreen.style.display = 'block';
        document.getElementById('fb-icon').innerText = isCorrect ? '✅' : '🚨';
        document.getElementById('fb-title').innerText = isCorrect ? 'THREAT NEUTRALIZED' : 'SYSTEM BREACHED';
        document.getElementById('fb-title').style.color = isCorrect ? 'var(--safe-color)' : 'var(--danger-color)';
        document.getElementById('fb-message').innerText = message;
    }

    function loadNextEmail() {
        currentIndex++;
        if (currentIndex < scenarios.length) {
            renderEmail();
        } else {
            document.getElementById('feedback-screen').style.display = 'none';
            document.getElementById('completion-screen').style.display = 'block';
        }
        updateUI();
    }

    function submitShift(force = false) {
        if(currentIndex < scenarios.length && !force) {
            if(!confirm("End shift anyway?")) return;
        }

        fetch('/spam/submit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
            
            // Fix: Only play the success sound if they actually survived
            if (stats.health > 0) {
                sfx.shiftEnd.currentTime = 0;
                sfx.shiftEnd.play();
            }

            const scoreDisplay = document.getElementById('final-score-display');
            const shiftTitle = document.querySelector('.success-title');
            
            // New: Grab the emoji and message elements
            const shiftEmoji = document.getElementById('shift-emoji');
            const shiftMessage = document.getElementById('shift-message');
            
            scoreDisplay.innerText = stats.health + '%';
            
            let statusColor, statusGlow;
            
            // Dynamic messaging based on health
            if (stats.health >= 80) {
                statusColor = 'var(--safe-color)';
                statusGlow = '0 0 20px rgba(34, 197, 94, 0.6)';
                shiftTitle.innerText = "SHIFT COMPLETE";
                shiftEmoji.innerText = "🏆";
                shiftMessage.innerText = "Excellent work protecting the network.";
            } else if (stats.health > 0) {
                statusColor = 'var(--module-accent)'; 
                statusGlow = '0 0 20px rgba(234, 179, 8, 0.6)'; 
                shiftTitle.innerText = "SHIFT COMPLETE";
                shiftEmoji.innerText = "⚠️";
                shiftMessage.innerText = "Shift finished, but the network sustained damage. Be more careful next time.";
            } else {
                statusColor = 'var(--danger-color)';
                statusGlow = '0 0 20px rgba(239, 68, 68, 0.6)';
                shiftTitle.innerText = "SYSTEM COMPROMISED";
                shiftEmoji.innerText = "🚨"; 
                shiftMessage.innerText = "Critical failure. The network has been overrun by threats.";
            }

            // Apply the styles
            scoreDisplay.style.color = statusColor;
            scoreDisplay.style.textShadow = statusGlow;
            shiftTitle.style.color = statusColor;
            shiftTitle.style.textShadow = statusGlow;

            document.getElementById('triage-workspace').style.display = 'none';
            document.getElementById('shift-complete-screen').style.display = 'flex';
        });
    }
    

    </script>
</body>
</html>