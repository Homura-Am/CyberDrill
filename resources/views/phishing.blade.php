<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phishing | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>

    <style>
        /* (Styles kept same as before) */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.85); z-index: 1000; justify-content: center; align-items: center; }
        .modal-content { background: #fff; border-radius: 8px; width: 95%; max-width: 800px; max-height: 90vh; overflow-y: auto; position: relative; display: flex; flex-direction: column; }
        .ui-gmail { background: #ffffff; color: #202124; font-family: Arial, sans-serif; border-radius: 8px 8px 0 0; }
        .gmail-header { background: #f1f3f4; padding: 15px 20px; border-bottom: 1px solid #e0e0e0; }
        .gmail-sender-row { display: flex; align-items: center; gap: 10px; }
        .gmail-avatar { width: 40px; height: 40px; border-radius: 50%; background: #5f6368; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .gmail-details { display: flex; flex-direction: column; font-size: 0.9rem; }
        .gmail-body { padding: 30px 20px; font-size: 1rem; line-height: 1.5; min-height: 150px; }
        .fake-link { color: #1a73e8; text-decoration: underline; cursor: pointer; }
        .fake-link:hover { background-color: rgba(26, 115, 232, 0.1); }
        .fake-btn { background:#1a73e8; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer; font-size: 0.9rem; font-weight: bold; }
        .ui-imessage-wrapper { background: #000; padding: 40px; border-radius: 8px 8px 0 0; display: flex; justify-content: center; }
        .ui-imessage { background: #000; color: white; font-family: -apple-system, sans-serif; width: 100%; max-width: 380px; border: 1px solid #333; border-radius: 20px; padding: 20px; }
        .imessage-header { text-align: center; border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 15px; }
        .imessage-avatar { width: 50px; height: 50px; background: #999; border-radius: 50%; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center; }
        .imessage-bubble { background: #262628; padding: 12px 18px; border-radius: 18px; border-bottom-left-radius: 4px; position: relative; }
        .imessage-link { color: #0b84ff; text-decoration: underline; cursor: pointer; }
        .quiz-area { background: var(--bg-card); padding: 20px; border-top: 1px solid var(--border-color); border-radius: 0 0 8px 8px; }
        .quiz-question { text-align: center; color: var(--text-muted); margin-bottom: 15px; font-weight: 600; }
        .option-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }
        .option-btn { padding: 15px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-main); border-radius: 6px; cursor: pointer; text-align: center; transition: 0.2s; font-weight: 600; }
        .option-btn:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
        .result-view { text-align: center; padding: 20px; animation: fadeIn 0.3s ease; }
        .result-icon { font-size: 3rem; margin-bottom: 10px; }
        .result-title { font-size: 1.5rem; font-weight: bold; margin-bottom: 10px; }
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .panel { background: var(--bg-card); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 20px; }
        .scenario-btn { display: flex; justify-content: space-between; padding: 15px; border-bottom: 1px solid var(--border-color); cursor: pointer; transition: 0.2s; }
        .scenario-btn:hover { background: rgba(255,255,255,0.05); }
        .scenario-disabled { opacity: 0.5; pointer-events: none; background: rgba(0,0,0,0.1); }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
        .badge-pending { background: #64748b; color: white; }
        .badge-correct { background: #22c55e; color: white; }
        .badge-incorrect { background: #ef4444; color: white; }
        .history-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9rem; }
        .history-table th { text-align: left; color: var(--text-muted); padding: 8px; border-bottom: 1px solid var(--border-color); }
        .history-table td { padding: 8px; border-bottom: 1px solid rgba(255,255,255,0.05); color: var(--text-main); }
        .score-high { color: #22c55e; font-weight: bold; }
        .score-mid { color: #f59e0b; font-weight: bold; }
        .score-low { color: #ef4444; font-weight: bold; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container dashboard-container">
        <h1 style="margin-bottom: 20px;">PHISHING SIMULATION</h1>
        <div class="progress-track"><div class="progress-fill" style="width: 0%; height: 5px; background: var(--primary-color);"></div></div>

        <div class="dashboard-grid">
            <div class="panel">
                <h3>Select a Scenario</h3>
                <div id="scenario-list">
                    <p style="color: var(--text-muted); padding: 20px;">Loading scenarios...</p>
                </div>
            </div>

            <div>
                <div class="panel">
                    <h3>Performance</h3>
                    <div style="height: 200px;"><canvas id="performanceChart"></canvas></div>
                    <button onclick="submitAttempt()" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                        ✅ Submit Attempt
                    </button>
                    <p style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-top: 10px;">
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
                                <tr>
                                    <td>{{ $attempt->created_at->format('M d, H:i') }}</td>
                                    <td>
                                        @if($attempt->score >= 80) <span class="score-high">{{ $attempt->score }}%</span>
                                        @elseif($attempt->score >= 50) <span class="score-mid">{{ $attempt->score }}%</span>
                                        @else <span class="score-low">{{ $attempt->score }}%</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" style="text-align: center; color: var(--text-muted);">No attempts yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="quiz-modal" class="modal-overlay">
        <div class="modal-content" id="modal-content-box"></div>
    </div>

    <script>
        // --- INJECT DATA FROM BACKEND ---
        const scenarios = @json($scenarios);
        let userProgress = @json($savedProgress);

        // DEBUGGING: Check console if empty
        console.log("Loaded Scenarios:", scenarios);

        const modal = document.getElementById('quiz-modal');
        const modalContent = document.getElementById('modal-content-box');
        let currentScenarioId = null;

        function renderList() {
            const list = document.getElementById('scenario-list');
            list.innerHTML = '';

            const keys = Object.keys(scenarios);
            if (keys.length === 0) {
                list.innerHTML = '<p style="padding:15px; color:orange;">No scenarios found. Please contact admin to seed database.</p>';
                return;
            }

            keys.forEach(key => {
                const item = scenarios[key];
                const status = userProgress[key] || 'pending';
                
                let badgeClass = 'badge-pending', badgeText = 'START';
                let isDisabled = false;

                if(status === 'correct') { badgeClass = 'badge-correct'; badgeText = 'PASSED'; isDisabled = true; }
                if(status === 'incorrect') { badgeClass = 'badge-incorrect'; badgeText = 'FAILED'; isDisabled = true; }

                const div = document.createElement('div');
                div.className = `scenario-btn ${isDisabled ? 'scenario-disabled' : ''}`;
                div.innerHTML = `<span>${item.type==='email'?'📧':'📱'} ${item.title}</span> <span class="badge ${badgeClass}">${badgeText}</span>`;
                
                if(!isDisabled) {
                    div.onclick = () => openSimulation(key);
                }
                list.appendChild(div);
            });
            updateChart();
            updateProgressBar();
        }

        function openSimulation(id) {
            currentScenarioId = id;
            const data = scenarios[id];
            
            // Generate HTML for Body
            let uiHtml = (data.type === 'email') 
                ? `<div class="ui-gmail"><div class="gmail-header"><div class="gmail-sender-row"><div class="gmail-avatar">${data.senderName.charAt(0)}</div><div class="gmail-details"><span style="font-weight:bold">${data.senderName}</span><span>&lt;${data.senderEmail}&gt;</span></div></div></div><div class="gmail-body">${data.body}</div></div>`
                : `<div class="ui-imessage-wrapper"><div class="ui-imessage"><div class="imessage-header"><div class="imessage-avatar">👤</div><div>${data.senderName}</div></div><div class="imessage-bubble">${data.body}</div></div></div>`;

            // Generate HTML for Buttons
            let btnsHtml = `<div class="option-grid">`;
            
            // Safety Check: ensure options is an array
            if(Array.isArray(data.options)) {
                data.options.forEach((opt, index) => {
                    btnsHtml += `<button class="option-btn" onclick="checkAnswer(${index})">${opt.text}</button>`;
                });
            } else {
                btnsHtml += `<p style="color:red">Error: Options data invalid.</p>`;
            }
            btnsHtml += `</div>`;

            modalContent.innerHTML = uiHtml + `<div class="quiz-area" id="action-area"><div class="quiz-question">Action?</div>${btnsHtml}<button onclick="closeModal()" class="btn btn-outline" style="margin-top:10px;width:100%">Cancel</button></div>`;
            modal.style.display = 'flex';
        }

        function closeModal() { modal.style.display = 'none'; }

        function checkAnswer(index) {
            const choice = scenarios[currentScenarioId].options[index];
            const actionArea = document.getElementById('action-area');
            
            if(choice.result === 'neutral') {
                actionArea.innerHTML = `<div class="result-view"><div class="result-icon">🤔</div><p>${choice.feedback}</p><button onclick="openSimulation('${currentScenarioId}')" class="btn btn-outline">Try Again</button></div>`;
                return;
            }
            
            userProgress[currentScenarioId] = choice.result;
            saveProgress(currentScenarioId, choice.result);
            renderList();
            
            let color = choice.result==='correct'?'#22c55e':'#ef4444';
            let icon = choice.result==='correct'?'✅':'❌';
            let title = choice.result==='correct'?'Excellent!':'Oops!';
            
            actionArea.innerHTML = `<div class="result-view"><div class="result-icon">${icon}</div><div class="result-title" style="color:${color}">${title}</div><p>${choice.feedback}</p><button onclick="closeModal()" class="btn btn-primary" style="background:${color};width:100%">Continue</button></div>`;
        }

        function saveProgress(id, status) {
            fetch('/phishing/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ scenario_id: id, status: status })
            });
        }

        function submitAttempt() {
            if(!confirm("Submit this score and start a new run?")) return;
            fetch('/phishing/submit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert("Run Complete! Final Score: " + data.score + "%");
                    window.location.reload();
                }
            });
        }

        function updateProgressBar() {
            const total = Object.keys(scenarios).length;
            const completed = Object.keys(userProgress).length;
            const percent = total > 0 ? (completed / total) * 100 : 0;
            document.querySelector('.progress-fill').style.width = percent + '%';
        }

        let myChart = null;
        function updateChart() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            let c=0, i=0, p=0;
            Object.keys(scenarios).forEach(k => {
                if(userProgress[k]==='correct') c++; else if(userProgress[k]==='incorrect') i++; else p++;
            });
            if(myChart) myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'doughnut',
                data: { labels: ['Correct', 'Incorrect', 'Pending'], datasets: [{ data: [c, i, p], backgroundColor: ['#22c55e', '#ef4444', '#64748b'], borderWidth: 0 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        renderList();
    </script>
</body>
</html>