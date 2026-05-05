<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spam | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @include('partials.navbar') <div class="container dashboard-container">
        <h1 style="margin-bottom: 20px;">SPAM ANALYSIS</h1>

        <div class="dashboard-grid">
            
            <div class="panel">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <h3>Inbox Filtering</h3>
                    <span style="font-size: 0.8rem;">🔍 Search</span>
                </div>
                
                <div class="email-row suspicious">
                    <span>From: <strong>urgent@pay-pal-security.com</strong></span>
                    <span>⚠️ High</span>
                </div>
                <div class="email-row">
                    <span>From: newsletter@company.com</span>
                    <span style="color: #22c55e;">Safe</span>
                </div>
                <div class="email-row suspicious">
                    <span>From: <strong>ceo.urgent@gmail.com</strong></span>
                    <span>⚠️ High</span>
                </div>
                <div class="email-row">
                    <span>From: support@google.com</span>
                    <span style="color: #22c55e;">Safe</span>
                </div>
                <div class="email-row suspicious">
                    <span>From: <strong>winner@lottery-claim.net</strong></span>
                    <span>🚫 Block</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <div class="panel">
                    <h3>Sender Reputation</h3>
                    <div class="score-gauge">SCORE: 85</div>
                    <div style="height: 6px; background: #333; border-radius: 4px; overflow: hidden;">
                        <div style="width: 85%; height: 100%; background: linear-gradient(90deg, #ef4444, #f59e0b, #22c55e);"></div>
                    </div>
                </div>

                <div class="panel">
                    <h3>Blacklist Management</h3>
                    <div class="checklist-item">
                        <input type="checkbox"> <span>Block TLD .xyz</span>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox" checked> <span>Auto-quarantine Executables</span>
                    </div>
                    <div class="checklist-item">
                        <input type="checkbox"> <span>Report to SpamHaus</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>