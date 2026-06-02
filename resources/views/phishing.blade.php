<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phishing | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>

    <style>
        /* Base Styles */
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .panel { background: var(--bg-card, #fff); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); margin-bottom: 20px; }
        .scenario-btn { display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid var(--border-color, #e2e8f0); cursor: pointer; transition: 0.2s; }
        .scenario-btn:hover { background: rgba(0,0,0,0.02); }
        .scenario-disabled { opacity: 0.6; pointer-events: none; background: rgba(0,0,0,0.05); }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .badge-pending { background: #64748b; color: white; }
        .badge-correct { background: #22c55e; color: white; }
        .badge-incorrect { background: #ef4444; color: white; }
        .history-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9rem; }
        .history-table th { text-align: left; color: var(--text-muted, #64748b); padding: 8px; border-bottom: 1px solid var(--border-color, #e2e8f0); }
        .history-table td { padding: 8px; border-bottom: 1px solid var(--border-color, #e2e8f0); color: var(--text-main, #0f172a); }
        .score-high { color: #22c55e; font-weight: bold; }
        .score-mid { color: #f59e0b; font-weight: bold; }
        .score-low { color: #ef4444; font-weight: bold; }

        /* Modal & Drag-and-Drop Styles */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; border-radius: 8px; width: 95%; max-width: 1000px; height: 80vh; overflow: hidden; position: relative; display: flex; flex-direction: column; }
        
        .ui-email-layout { display: flex; height: 100%; width: 100%; }
        .email-sidebar { width: 200px; background: #f8fafc; border-right: 1px solid #e2e8f0; padding: 10px; }
        .sidebar-item { padding: 10px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 10px; color: #475569; }
        .sidebar-item.active { background: #e2e8f0; font-weight: 600; color: #0f172a; }
        .email-main { flex: 1; display: flex; flex-direction: column; background: white; }
        .email-toolbar { padding: 10px 20px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 15px; }
        .icon-btn { display: flex; align-items: center; gap: 5px; cursor: pointer; padding: 5px 10px; border-radius: 4px; font-size: 0.9rem; color: #475569; }
        .icon-btn:hover { background: #f1f5f9; }
        .email-content-scroll { padding: 30px; overflow-y: auto; flex: 1; }
        .email-subject { font-size: 1.5rem; margin-bottom: 20px; color: #0f172a; }
        .email-sender-row { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #e2e8f0; }
        .avatar { width: 45px; height: 45px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; }
        .email-body { font-size: 1.05rem; line-height: 1.6; color: #334155; }
        .fake-link { color: #2563eb; text-decoration: underline; cursor: pointer; }

        /* Center-Out Pulse Animation for Drop Zones */
        @keyframes pulse-center-out {
            0% { transform: scale(0); opacity: 0.8; }
            100% { transform: scale(1); opacity: 0; }
        }

        .drop-zone { border: 2px dashed transparent; border-radius: 8px; position: relative; transition: 0.2s; padding: 10px; margin: -10px; z-index: 1; }
        .drop-zone > * { position: relative; z-index: 2; } 
        
        .drop-zone.active-drag { border: 2px dashed rgba(59, 130, 246, 0.6); }
        .drop-zone.active-drag::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(59, 130, 246, 0.5);
            border-radius: inherit;
            animation: pulse-center-out 1s infinite ease-out;
            pointer-events: none;
            z-index: 0;
        }

        .drop-zone.drag-over { border-color: #3b82f6 !important; background: rgba(59, 130, 246, 0.1) !important; }
        .drop-zone.drag-over::before { display: none; } 
        
        .placed-flag { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); z-index: 3; }

        .audit-panel { width: 320px; background: #f8fafc; border-left: 1px solid #e2e8f0; padding: 20px; display: flex; flex-direction: column; overflow-y: auto; }
        .audit-header { font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; margin-bottom: 15px; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px; }
        
        .draggable-flag { padding: 12px; background: #ffffff !important; color: #0f172a !important; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 10px; cursor: grab; display: flex; align-items: center; gap: 10px; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .draggable-flag:active { cursor: grabbing; }
        .flag-safe { color: #16a34a; }
        .flag-danger { color: #dc2626; }

        .btn-modern { padding: 10px 20px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .btn-ghost { background: transparent; border: 1px solid #e2e8f0; color: #475569; }
        .btn-ghost:hover { background: #f1f5f9; }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fca5a5; }

        .warning-overlay { position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(255,255,255,0.95); z-index: 10; display: none; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 20px; }
        
        .ui-phone-container { width: 100%; height: 100%; background: #f1f5f9; display: flex; justify-content: center; align-items: center; position: relative; }
        .smartphone { width: 340px; height: 680px; background: white; border-radius: 45px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 12px solid #0f172a; display: flex; flex-direction: column; position: relative; overflow: hidden; }
        .phone-notch { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 140px; height: 30px; background: #0f172a; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; z-index: 2; }
        .sms-header { background: #f8fafc; padding: 45px 15px 15px; text-align: center; border-bottom: 1px solid #e2e8f0; }
        .sms-avatar { width: 50px; height: 50px; background: #cbd5e1; border-radius: 50%; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; }
        .sms-sender { font-weight: 600; color: #0f172a; font-size: 1.1rem; }
        .sms-chat-area { flex: 1; padding: 20px; background: white; overflow-y: auto; }
        .sms-bubble { background: #e2e8f0; padding: 12px 16px; border-radius: 18px; border-bottom-left-radius: 4px; max-width: 85%; font-size: 0.95rem; line-height: 1.4; color: #0f172a; position: relative; }
        .sms-meta { text-align: center; font-size: 0.75rem; color: #94a3b8; margin-bottom: 15px; }
        .sms-input { padding: 15px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; }
        .sms-pill { flex: 1; border: 1px solid #cbd5e1; border-radius: 20px; padding: 8px 15px; color: #94a3b8; font-size: 0.9rem; }
        .audit-panel-phone { position: absolute; right: 40px; top: 50%; transform: translateY(-50%); width: 300px; /* --- NEW SCROLL FIX --- */max-height: 80vh; /* Keeps the panel smaller than the screen height */overflow-y: auto; 
        /* Enables vertical scrolling */display: flex;flex-direction: column;background: white; padding: 20px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .result-view { text-align: center; padding: 40px; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%; background: white; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container dashboard-container" style="padding: 20px; max-width: 1200px; margin: 0 auto;">
        <h1 style="margin-bottom: 20px;">PHISHING SIMULATION</h1>
        <div class="progress-track" style="background: var(--border-color, #e2e8f0); border-radius: 4px; overflow: hidden; margin-bottom: 20px;">
            <div class="progress-fill" style="width: 0%; height: 5px; background: var(--primary-color, #3b82f6); transition: 0.3s;"></div>
        </div>

        <div class="dashboard-grid">
            <div class="panel">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0;">Select a Scenario</h3>
                    <span class="material-symbols-outlined" style="cursor: pointer; color: #64748b; font-size: 1.5rem; transition: 0.2s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'" onclick="checkInstructions()" title="View Instructions">info</span>
                </div>
                <div id="scenario-list">
                    <p style="color: var(--text-muted); padding: 20px;">Loading scenarios...</p>
                </div>
            </div>

            <div>
                <div class="panel">
                    <h3>Performance</h3>
                    <div style="height: 200px;"><canvas id="performanceChart"></canvas></div>
                    <button onclick="submitAttempt()" class="btn btn-primary" style="width: 100%; margin-top: 20px; padding: 10px; font-weight: bold; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        ✅ Submit Attempt
                    </button>
                    <p style="font-size: 0.8rem; color: var(--text-muted, #64748b); text-align: center; margin-top: 10px;">
                        Submitting records your score and resets the simulation.
                    </p>
                </div>

                <div class="panel">
                    <h3>Previous Attempts</h3>
                    <table class="history-table">
                        <thead>
                            <tr><th>Date</th><th>Score</th></tr>
                        </thead>
                        <tbody>
                            @forelse($attempts as $attempt)
                                @if($loop->index < 5)
                                    <tr>
                                        <td>{{ $attempt->created_at->format('M d, H:i') }}</td>
                                        <td>
                                            @if($attempt->score >= 80) <span class="score-high">{{ $attempt->score }}%</span>
                                            @elseif($attempt->score >= 50) <span class="score-mid">{{ $attempt->score }}%</span>
                                            @else <span class="score-low">{{ $attempt->score }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr><td colspan="2" style="text-align: center; color: var(--text-muted, #64748b);">No attempts yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="instruction-modal" class="modal-overlay" style="z-index: 2000;">
        <div class="modal-content" style="max-width: 600px; height: auto; padding: 40px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; background: #0f172a; border: 1px solid #1e293b; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
            <span class="material-symbols-outlined" style="font-size: 4rem; color: #3b82f6; margin-bottom: 15px;">school</span>
            <h2 style="font-size: 1.8rem; color: #f8fafc; margin-bottom: 20px;">Welcome to the Simulation</h2>
            <div style="font-size: 1.05rem; color: #cbd5e1; text-align: left; line-height: 1.6; width: 100%; margin-bottom: 30px;">
                <p style="margin-bottom: 10px;"><strong style="color: white;">Your Task:</strong> Evaluate incoming communications for cyber threats.</p>
                <ul style="padding-left: 20px; margin-bottom: 15px;">
                    <li style="margin-bottom: 8px;">Select a pending scenario from your dashboard.</li>
                    <li style="margin-bottom: 8px;">Analyze the message, sender, and links. <br><em style="color:#94a3b8; font-size: 0.95rem;">(Hover over links/buttons to reveal their hidden destination)</em></li>
                    <li style="margin-bottom: 8px;">Drag the <span style="color: #22c55e; font-weight: bold;">Safe</span> or <span style="color: #ef4444; font-weight: bold;">Suspicious</span> flags onto the highlighted pulsing zones.</li>
                    <li style="margin-bottom: 8px;">Choose a final action from the toolbar (Report, Reply, Archive, etc.).</li>
                </ul>
                <p>If you get stuck, use the <strong>Hint</strong> button inside the scenario.</p>
            </div>
            <button onclick="dismissInstructions()" class="btn-modern" style="background: #3b82f6; color: white; padding: 12px 40px; font-size: 1.1rem; border: none;">I Understand</button>
        </div>
    </div>

    <div id="simulation-modal" class="modal-overlay">
        <div class="modal-content" id="modal-content-box"></div>
    </div>
    <div class="module-footer">
    <p><strong>Scenario References & Copyright:</strong> Educational scenarios and social engineering artifacts are adapted for academic purposes, inspired by industry-standard training frameworks including the <a href="https://phishingquiz.withgoogle.com/" target="_blank">Google Jigsaw Phishing Quiz</a> (Google, 2018), <a href="https://cofense.com/" target="_blank">Cofense PhishMe</a>, and <a href="https://www.knowbe4.com/" target="_blank">KnowBe4</a>.</p>
    <p style="margin-top: 8px; opacity: 0.7;">© {{ date('Y') }} CyberDrill Simulation Platform. All rights reserved for educational use.</p>
</div>

    <script>
        // --- SOUND EFFECTS SETUP ---
        const sfxCorrect = new Audio('/sounds/correct.mp3'); 
        const sfxWrong = new Audio('/sounds/wrong.mp3');     
        const sfxSubmit = new Audio('/sounds/submit.mp3'); 
        const sfxDrop = new Audio('/sounds/drop.mp3'); 
        const sfxOpen = new Audio('/sounds/open.mp3'); 
        
        sfxCorrect.volume = 0.6;
        sfxWrong.volume = 0.6;
        sfxSubmit.volume = 0.8;
        sfxDrop.volume = 0.6;
        sfxOpen.volume = 0.5;

        const scenarios = @json($scenarios ?? (object)[]);
        let rawProgress = @json($savedProgress ?? (object)[]);
        let userProgress = (Array.isArray(rawProgress) && rawProgress.length === 0) ? {} : rawProgress;
        
        let currentScenarioId = null;
        let requiredZones = 3; 
        let activeFlags = {};
        let draggedFlagColor = null; 

        function checkInstructions() { document.getElementById('instruction-modal').style.display = 'flex'; }
        function dismissInstructions() { document.getElementById('instruction-modal').style.display = 'none'; }

        function renderList() {
            const list = document.getElementById('scenario-list');
            list.innerHTML = '';
            const keys = Object.keys(scenarios);
            
            if (keys.length === 0) {
                list.innerHTML = '<p style="padding:15px; color:orange;">No scenarios found. Database may be empty.</p>';
                return;
            }

            keys.forEach(key => {
                const item = scenarios[key];
                const status = userProgress[key] || 'pending';
                let badgeClass = 'badge-pending', badgeText = 'PENDING', isDisabled = false;

                if(status === 'correct') { badgeClass = 'badge-correct'; badgeText = 'SECURED'; isDisabled = true; }
                if(status === 'incorrect') { badgeClass = 'badge-incorrect'; badgeText = 'COMPROMISED'; isDisabled = true; }

                const div = document.createElement('div');
                div.className = `scenario-btn ${isDisabled ? 'scenario-disabled' : ''}`;
                
                div.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span class="material-symbols-outlined" style="background: rgba(0,0,0,0.05); padding: 12px; border-radius: 8px;">${item.type === 'email' ? 'mail' : 'smartphone'}</span>
                        <div style="display: flex; flex-direction: column;">
                            <strong style="color: var(--text-main); font-size: 1rem;">${item.sender_name || item.senderName || 'Sender'}</strong>
                            <span style="color: var(--text-muted); font-size: 0.85rem;">${item.title || item.subject || 'Message'}</span>
                        </div>
                    </div>
                    <span class="badge ${badgeClass}">${badgeText}</span>
                `;
                
                if(!isDisabled) div.onclick = () => openSimulation(key);
                list.appendChild(div);
            });
            updateChart();
            updateProgressBar();
        }

        function updateProgressBar() {
            const keys = Object.keys(scenarios);
            const percent = keys.length ? (Object.keys(userProgress).length / keys.length * 100) : 0;
            document.querySelector('.progress-fill').style.width = percent + '%';
        }

        function processBodyHtml(htmlString, isSms) {
            if (isSms) {
                return htmlString.replace(/(https?:\/\/[^\s]+)/gi, '<a href="#" class="fake-link" title="$1" style="color: #2563eb; text-decoration: underline;" onclick="handleAction(\'link\'); return false;">$1</a>');
            }
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');
            let destUrl = '';
            
            doc.querySelectorAll('span').forEach(span => {
                if(span.textContent.toLowerCase().includes('destination:')) {
                    destUrl = span.textContent.split(/destination:/i)[1].trim();
                    let p = span.closest('p');
                    if(p) p.remove(); else span.remove();
                }
            });

            doc.querySelectorAll('a').forEach(a => {
                a.className = 'fake-link';
                a.title = destUrl || a.href || 'http://unknown-destination.com';
                a.setAttribute('onclick', "handleAction('link'); return false;");
                a.href = '#';
            });

            doc.querySelectorAll('button').forEach(btn => {
                btn.title = destUrl || 'http://unknown-destination.com';
                btn.setAttribute('onclick', "handleAction('link'); return false;");
            });
            return doc.body.innerHTML;
        }

        function openSimulation(id) {
            currentScenarioId = id;
            const data = scenarios[id];
            
            const senderName = data.sender_name || data.senderName;
            const senderEmail = data.sender_email || data.senderEmail || 'unknown@domain.com';
            const subject = data.title || data.subject;
            
            requiredZones = data.type === 'email' ? 3 : 2;
            activeFlags = {};
            for(let i=1; i<=requiredZones; i++) activeFlags['zone'+i] = null;

            const processedBody = processBodyHtml(data.body, data.type === 'sms');

            const warningOverlay = `
                <div id="warning-overlay" class="warning-overlay">
                    <h2 style="color: #dc2626; font-size: 1.8rem; margin-bottom: 10px; font-weight: 700; display: flex; align-items: center; gap: 10px;"><span class="material-symbols-outlined">warning</span> Warning</h2>
                    <p style="color: #000000; font-size: 1.05rem; max-width: 450px; margin-bottom: 30px;">You marked part of this message as a threat, but you are attempting to interact with it. Proceed?</p>
                    <div style="display: flex; gap: 15px;">
                        <button class="btn-modern btn-danger" onclick="processAction('report')">Report Threat</button>
                        <button class="btn-modern btn-ghost" onclick="processAction(pendingAction)">Proceed Anyway</button>
                    </div>
                </div>
            `;

            const auditPanelHtml = `
                <div class="audit-header"><span class="material-symbols-outlined" style="font-size: 1.2rem;">fact_check</span> Audit Tools</div>
                <p style="font-size: 0.85rem; color: #64748b; line-height: 1.5; margin-bottom: 15px;">Drag and drop markers onto the highlighted zones to evaluate this message.</p>
                
                <div class="draggable-flag" draggable="true" data-color="green" ondragstart="drag(event)" ondragend="dragEnd(event)">
                    <span class="material-symbols-outlined flag-safe">check_circle</span> Mark Safe
                </div>
                <div class="draggable-flag" draggable="true" data-color="red" ondragstart="drag(event)" ondragend="dragEnd(event)">
                    <span class="material-symbols-outlined flag-danger">flag</span> Mark Suspicious
                </div>

                <div style="margin-top: auto;">
                    <div id="hint-box" style="display:none; background: #fffbeb; border: 1px solid #fde68a; padding: 12px; border-radius: 8px; font-size: 0.85rem; color: #92400e; margin-bottom: 15px; line-height: 1.4;">
                        <strong>Analysis Hint:</strong> ${data.feedback || 'Review the highlighted zones.'}
                    </div>
                    <button id="hint-btn" class="btn-modern btn-ghost" style="width: 100%; margin-bottom: 15px; justify-content: center; background: #fffbeb; color: #d97706; border-color: #fde68a;" onclick="document.getElementById('hint-box').style.display='block'; this.style.display='none';">
                        <span class="material-symbols-outlined">lightbulb</span> Need a Hint?
                    </button>

                    <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 10px;">Simulation Actions</div>
                    <button class="btn-modern btn-ghost" style="width: 100%; margin-bottom: 10px; justify-content: center;" onclick="handleAction('back')">Save & Exit</button>
                </div>
            `;

            let htmlContent = warningOverlay;

            if (data.type === 'email') {
                htmlContent += `
                    <div class="ui-email-layout">
                        <div class="email-sidebar">
                            <div class="sidebar-item active"><span class="material-symbols-outlined">inbox</span> Inbox</div>
                            <div class="sidebar-item"><span class="material-symbols-outlined">send</span> Sent</div>
                            <div class="sidebar-item"><span class="material-symbols-outlined">draft</span> Drafts</div>
                        </div>
                        
                        <div class="email-main">
                            <div class="email-toolbar">
                                <div class="icon-btn" onclick="handleAction('report')" style="color: #dc2626; background: #fee2e2; font-weight: bold;"><span class="material-symbols-outlined">report</span> Report Phishing</div>
                                <div style="width: 1px; background: #e2e8f0; margin: 0 10px;"></div>
                                <div class="icon-btn"><span class="material-symbols-outlined">archive</span> Archive</div>
                                <div class="icon-btn"><span class="material-symbols-outlined">delete</span> Delete</div>
                            </div>

                            <div class="email-content-scroll">
                                <div class="drop-zone" id="zone-1" ondragover="allowDrop(event)" ondrop="drop(event, 'zone1')" ondragleave="dragLeave(event)">
                                    <h2 class="email-subject">${subject}</h2>
                                    <span class="placed-flag" id="flag-zone1"></span>
                                </div>

                                <div class="drop-zone email-sender-row" id="zone-2" ondragover="allowDrop(event)" ondrop="drop(event, 'zone2')" ondragleave="dragLeave(event)">
                                    <div class="avatar">${senderName.charAt(0)}</div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.95rem; color: #0f172a;">${senderName} <span style="font-weight: 400; color: #64748b; font-size: 0.85rem;">&lt;${senderEmail}&gt;</span></div>
                                        <div style="color: #64748b; font-size: 0.8rem;">to me ▾</div>
                                    </div>
                                    <span class="placed-flag" id="flag-zone2"></span>
                                </div>

                                <div class="drop-zone email-body" id="zone-3" ondragover="allowDrop(event)" ondrop="drop(event, 'zone3')" ondragleave="dragLeave(event)">
                                    ${processedBody}
                                    <span class="placed-flag" id="flag-zone3"></span>
                                </div>
                                
                                <div style="margin-top: 30px; display: flex; gap: 10px;">
                                    <button class="btn-modern btn-ghost" onclick="handleAction('reply')"><span class="material-symbols-outlined">reply</span> Reply</button>
                                    <button class="btn-modern btn-ghost" onclick="handleAction('forward')"><span class="material-symbols-outlined">forward</span> Forward</button>
                                </div>
                            </div>
                        </div>

                        <div class="audit-panel">${auditPanelHtml}</div>
                    </div>
                `;
            } else {
                htmlContent += `
                    <div class="ui-phone-container">
                        <div style="position: absolute; top: 20px; left: 20px;">
                            <button class="btn-modern btn-ghost" style="background: white;" onclick="handleAction('back')"><span class="material-symbols-outlined">arrow_back</span> Dashboard</button>
                        </div>

                        <div class="smartphone">
                            <div class="phone-notch"></div>
                            <div class="sms-header drop-zone" id="zone-1" ondragover="allowDrop(event)" ondrop="drop(event, 'zone1')" ondragleave="dragLeave(event)">
                                <div class="sms-avatar"><span class="material-symbols-outlined">person</span></div>
                                <div class="sms-sender">${senderName} &gt;</div>
                                <span class="placed-flag" id="flag-zone1"></span>
                            </div>
                            <div class="sms-chat-area">
                                <div class="drop-zone" id="zone-2" ondragover="allowDrop(event)" ondrop="drop(event, 'zone2')" ondragleave="dragLeave(event)">
                                    <div class="sms-meta">Text Message • Today 9:41 AM</div>
                                    <div class="sms-bubble">${processedBody}</div>
                                    <span class="placed-flag" id="flag-zone2"></span>
                                </div>
                            </div>
                            <div class="sms-input">
                                <span class="material-symbols-outlined" style="color: #94a3b8;">add_circle</span>
                                <div class="sms-pill" onclick="handleAction('reply')">Text Message</div>
                            </div>
                        </div>

                        <div class="audit-panel-phone">
                            <button class="btn-modern btn-danger" style="width: 100%; justify-content: center; margin-bottom: 15px;" onclick="handleAction('report')"><span class="material-symbols-outlined">report</span> Report Smishing</button>
                            <div style="border-top: 1px solid #e2e8f0; margin-bottom: 15px;"></div>
                            ${auditPanelHtml}
                        </div>
                    </div>
                `;
            }
            
            const modal = document.getElementById('simulation-modal');
            document.getElementById('modal-content-box').innerHTML = htmlContent;
            modal.style.display = 'flex';
        }

        // --- DRAG AND DROP HANDLERS ---
        function drag(ev) { 
            draggedFlagColor = ev.currentTarget.getAttribute('data-color');
            ev.dataTransfer.setData("text/plain", draggedFlagColor);
            document.querySelectorAll('.drop-zone').forEach(zone => zone.classList.add('active-drag'));
        }
        function dragEnd(ev) { document.querySelectorAll('.drop-zone').forEach(zone => zone.classList.remove('active-drag')); }
        function allowDrop(ev) { ev.preventDefault(); ev.currentTarget.classList.add('drag-over'); }
        function dragLeave(ev) { ev.currentTarget.classList.remove('drag-over'); }
        
        function drop(ev, zoneId) {
            ev.preventDefault();
            ev.currentTarget.classList.remove('drag-over');
            document.querySelectorAll('.drop-zone').forEach(zone => zone.classList.remove('active-drag'));
            
            const color = ev.dataTransfer.getData("text/plain") || draggedFlagColor;
            if (!color) return; 

            document.getElementById(`flag-${zoneId}`).innerHTML = color === 'red' ? '<span class="material-symbols-outlined flag-danger">flag</span>' : '<span class="material-symbols-outlined flag-safe">check_circle</span>';
            activeFlags[zoneId] = color;
            
            sfxDrop.currentTime = 0;
            sfxDrop.play().catch(e => console.log("Audio play blocked by browser:", e));
        }

        let pendingAction = null; 

        function handleAction(actionType, bypassWarning = false) {
            if (actionType === 'back' && !Object.values(activeFlags).some(v => v !== null)) {
                document.getElementById('simulation-modal').style.display = 'none'; return;
            }
            if (Object.values(activeFlags).includes(null)) {
                alert(`Please drag a safe or suspicious flag onto all ${requiredZones} dashed zones before making a final action.`);
                return;
            }
            if (actionType !== 'report' && Object.values(activeFlags).includes('red') && !bypassWarning) {
                pendingAction = actionType; document.getElementById('warning-overlay').style.display = 'flex'; return;
            }
            processAction(actionType);
        }

        function processAction(actionType) {
            const scenario = scenarios[currentScenarioId];
            const isPhishing = scenario.is_phishing === true || scenario.is_phishing === '1' || scenario.is_phishing === 1; 
            
            let targetZones = [];
            let rawZone = String(scenario.malicious_zone || '').toLowerCase();
            
            // --- UPDATED MULTI-ZONE SUPPORT ---
            // If rawZone contains multiple keywords (e.g. "sender, link"), both will be pushed to the array.
            if (rawZone.includes('subject') || rawZone.includes('header') || rawZone.includes('zone1')) targetZones.push('zone1');
            if (rawZone.includes('sender') || rawZone.includes('email') || rawZone.includes('zone2')) targetZones.push('zone2');
            if (rawZone.includes('body') || rawZone.includes('link') || rawZone.includes('content') || rawZone.includes('zone3')) targetZones.push('zone3');
            
            // Fallback if phishing is true but no zone specified
            if (isPhishing && targetZones.length === 0) {
                targetZones.push(scenario.type === 'email' ? 'zone3' : 'zone2');
            }

            let correctFlags = 0;
            let zoneFeedbackHTML = '';

            for (let i = 1; i <= requiredZones; i++) {
                const z = 'zone' + i;
                const isZoneMalicious = targetZones.includes(z);
                
                let isCorrect = false;
                if (isZoneMalicious) { 
                    if (activeFlags[z] === 'red') { correctFlags++; isCorrect = true; }
                } else { 
                    if (activeFlags[z] === 'green') { correctFlags++; isCorrect = true; }
                }

                const zoneName = i === 1 ? (scenario.type === 'email' ? 'Subject / Header' : 'Message Header') : (i === 2 ? (scenario.type === 'email' ? 'Sender Identity' : 'Message Body') : 'Message Body / Links');
                
                // --- NEW DYNAMIC EXPLANATION LOGIC ---
                let zoneExplanation = "";
                let customFeedback = scenario['feedback_' + z] || null; 
                
                if (customFeedback) {
                    zoneExplanation = customFeedback;
                } else {
                    if (isZoneMalicious) {
                        if (i === 1) zoneExplanation = "This section uses urgent, alarming, or irregular language typical of social engineering.";
                        if (i === 2) zoneExplanation = "The sender details are forged, misspelled, or originate from an untrusted external domain.";
                        if (i === 3) zoneExplanation = "The content contains suspicious links, unexpected attachments, or requests for sensitive data.";
                    } else {
                        if (i === 1) zoneExplanation = "The context and language used here match normal, expected communication patterns.";
                        if (i === 2) zoneExplanation = "The sender's address and domain match the verified organization's official records.";
                        if (i === 3) zoneExplanation = "The message body is standard and links/destinations safely match their display text.";
                    }
                }

                zoneFeedbackHTML += `
                    <div style="margin-bottom: 12px; padding: 12px; background: ${isCorrect ? '#f0fdf4' : '#fef2f2'}; border-radius: 8px; border: 1px solid ${isCorrect ? '#bbf7d0' : '#fecaca'}; display: flex; align-items: flex-start; gap: 10px;">
                        <span class="material-symbols-outlined" style="color: ${isCorrect ? '#16a34a' : '#dc2626'}; font-size: 1.5rem; margin-top: 2px;">${isCorrect ? 'check_circle' : 'cancel'}</span>
                        <div style="display: flex; flex-direction: column; width: 100%;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">${zoneName}</div>
                            <div style="color: #475569; font-size: 0.85rem; margin-top: 4px;">
                                You marked: <strong style="color: ${activeFlags[z] === 'red' ? '#dc2626' : '#16a34a'}">${activeFlags[z] === 'red' ? 'Suspicious' : 'Safe'}</strong> 
                                <span style="opacity: 0.7;">(Should be ${isZoneMalicious ? 'Suspicious' : 'Safe'})</span>
                            </div>
                            <div style="color: ${isCorrect ? '#166534' : '#991b1b'}; font-size: 0.85rem; margin-top: 8px; padding-top: 8px; border-top: 1px dashed ${isCorrect ? '#bbf7d0' : '#fecaca'}; line-height: 1.4;">
                                <strong>Analysis:</strong> ${zoneExplanation}
                            </div>
                        </div>
                    </div>
                `;

                // Highlight the actual drop zones in the inbox visually
                const zoneEl = document.getElementById('zone-' + i);
                if (zoneEl) {
                    zoneEl.style.border = isCorrect ? '2px solid #16a34a' : '2px solid #dc2626';
                    zoneEl.style.backgroundColor = isCorrect ? 'rgba(22, 163, 74, 0.05)' : 'rgba(220, 38, 38, 0.05)';
                    zoneEl.style.borderRadius = '8px';
                }
            }

            let result = (actionType === 'report' && isPhishing) || (actionType !== 'report' && !isPhishing) ? 'correct' : 'incorrect';
            
            if (result === 'correct') {
                sfxCorrect.currentTime = 0; sfxCorrect.play().catch(e => console.log("Audio block:", e));
            } else {
                sfxWrong.currentTime = 0; sfxWrong.play().catch(e => console.log("Audio block:", e));
            }

            const auditPanel = document.querySelector('.audit-panel') || document.querySelector('.audit-panel-phone');
            
            if (auditPanel) {
                const warningOl = document.getElementById('warning-overlay');
                if (warningOl) warningOl.style.display = 'none';

                auditPanel.innerHTML = `
                    <div style="text-align: center; margin-bottom: 20px;">
                        <span class="material-symbols-outlined" style="color: ${result === 'correct' ? '#16a34a' : '#dc2626'}; font-size: 4rem;">${result === 'correct' ? 'gpp_good' : 'warning'}</span>
                        <h2 style="color: ${result === 'correct' ? '#16a34a' : '#dc2626'}; font-size: 1.4rem; margin: 5px 0 15px;">${result === 'correct' ? 'Correct Action' : 'Action Failed'}</h2>
                        <div style="display: inline-block; background: #e2e8f0; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; color: #0f172a;">Flags Accurate: ${correctFlags} / ${requiredZones}</div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4 style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; margin-bottom: 10px;">Flag Breakdown</h4>
                        ${zoneFeedbackHTML}
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; font-size: 0.9rem; color: #334155; margin-bottom: 20px; line-height: 1.5;">
                        <strong style="color: #0f172a;">Overall Scenario Feedback:</strong><br/>
                        ${scenario.feedback || 'Review the detailed analysis tags above for more insight on this communication.'}
                    </div>
                    
                    <button onclick="closeSimulationAndSave('${result}')" class="btn-modern" style="width: 100%; justify-content: center; background: ${result === 'correct' ? '#16a34a' : '#dc2626'}; color: white; padding: 12px; font-size: 1rem;">Save & Return</button>
                `;
            }
        }

        function closeSimulationAndSave(status) {
            userProgress[currentScenarioId] = status;
            fetch('/phishing/update', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, 
                body: JSON.stringify({ scenario_id: currentScenarioId, status: status }) 
            }).catch(error => console.error("Database Save Error:", error));

            document.getElementById('simulation-modal').style.display = 'none';
            renderList();
        }

        let myChart = null;
        function updateChart() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            let c=0, i=0, p=0; 
            Object.keys(scenarios).forEach(k => { if(userProgress[k]==='correct') c++; else if(userProgress[k]==='incorrect') i++; else p++; });
            if(myChart) myChart.destroy();
            myChart = new Chart(ctx, { 
                type: 'doughnut', 
                data: { labels: ['Secured', 'Compromised', 'Pending'], datasets: [{ data: [c, i, p], backgroundColor: ['#22c55e', '#ef4444', '#e2e8f0'], borderWidth: 0 }] }, 
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '75%' } 
            });
        }

        function submitAttempt() {
            const totalScenarios = Object.keys(scenarios).length;
            const completedScenarios = Object.keys(userProgress).length;

            if (completedScenarios < totalScenarios) {
                const proceed = confirm(`You have only completed ${completedScenarios} out of ${totalScenarios} scenarios. Are you sure you want to submit?`);
                if (!proceed) return;
            }

            fetch('/phishing/submit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    sfxSubmit.currentTime = 0; sfxSubmit.play().catch(e => console.log("Audio block:", e));
                    
                    const modal = document.getElementById('simulation-modal');
                    const contentBox = document.getElementById('modal-content-box');
                    
                    let scoreColor = data.score >= 80 ? '#22c55e' : (data.score >= 50 ? '#f59e0b' : '#ef4444');
                    let scoreIcon = data.score >= 80 ? 'verified' : (data.score >= 50 ? 'warning' : 'dangerous');
                    let message = data.score >= 80 ? 'Excellent work! You effectively identified the communication threats.' : 'Training required. Please review the guidelines and try again.';

                    contentBox.innerHTML = `
                        <div class="result-view" style="background: #0f172a; color: white;">
                            <span class="material-symbols-outlined" style="color: ${scoreColor}; font-size: 6rem; margin-bottom: 10px;">${scoreIcon}</span>
                            <h1 style="color: #f8fafc; font-size: 3rem; margin-bottom: 10px;">Simulation Completed</h1>
                            <div style="font-size: 4rem; font-weight: bold; color: ${scoreColor}; margin-bottom: 20px;">${data.score}%</div>
                            <p style="font-size: 1.2rem; color: #cbd5e1; max-width: 500px; margin-bottom: 40px;">${message}</p>
                            <button onclick="window.location.reload()" class="btn-modern" style="background: #3b82f6; color: white; padding: 15px 40px; font-size: 1.2rem;">Finish</button>
                        </div>
                    `;
                    
                    modal.style.display = 'flex';
                } else {
                    alert("Error processing the score.");
                }
            })
            .catch(err => {
                console.error("Submission Error", err);
                alert("Server error. Check console.");
            });
        }

        window.onload = () => { 
            checkInstructions();
            renderList(); 
            sfxOpen.currentTime = 0; sfxOpen.play().catch(e => console.log("Audio play blocked by browser:", e));
        };
    </script>
</body>
</html>