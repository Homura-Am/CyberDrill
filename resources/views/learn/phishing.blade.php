<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learn: Phishing | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .learn-container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .hero-section { text-align: center; margin-bottom: 40px; }
        .hero-icon { font-size: 4rem; margin-bottom: 10px; display: inline-block; animation: float 3s ease-in-out infinite; }
        .section-box { background: var(--bg-card); padding: 30px; border-radius: 12px; margin-bottom: 30px; border: 1px solid var(--border-color); }
        .section-title { color: var(--primary-color); font-size: 1.5rem; margin-bottom: 15px; font-weight: bold; }
        .content-text { line-height: 1.8; color: var(--text-main); font-size: 1.05rem; }
        .tip-list { list-style: none; padding: 0; }
        .tip-item { display: flex; align-items: start; margin-bottom: 15px; }
        .tip-icon { color: #22c55e; margin-right: 15px; font-size: 1.2rem; }
        
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container learn-container">
        
        <div class="hero-section">
            <div class="hero-icon">🎣</div>
            <h1>Understanding Phishing</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">The art of deception in the digital age.</p>
        </div>

        <div class="section-box">
            <h2 class="section-title">What is Phishing?</h2>
            <p class="content-text">
                Phishing is a type of cyber attack where attackers pose as legitimate institutions (like banks, your CEO, or Netflix) to trick individuals into revealing sensitive data. This often includes passwords, credit card numbers, or personal information.
            </p>
        </div>

        <div class="section-box">
            <h2 class="section-title">Common Types of Phishing</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                <div style="background: var(--bg-body); padding: 15px; border-radius: 8px;">
                    <strong style="color: var(--primary-color);">📧 Email Phishing</strong>
                    <p style="font-size: 0.9rem; margin-top: 5px;">Mass emails sent to thousands of users asking them to click a malicious link.</p>
                </div>
                <div style="background: var(--bg-body); padding: 15px; border-radius: 8px;">
                    <strong style="color: var(--primary-color);">🎯 Spear Phishing</strong>
                    <p style="font-size: 0.9rem; margin-top: 5px;">Highly targeted attacks personalized for a specific individual or company.</p>
                </div>
                <div style="background: var(--bg-body); padding: 15px; border-radius: 8px;">
                    <strong style="color: var(--primary-color);">🐋 Whaling</strong>
                    <p style="font-size: 0.9rem; margin-top: 5px;">Targeting high-profile executives (CEOs, CFOs) to steal critical company data.</p>
                </div>
                <div style="background: var(--bg-body); padding: 15px; border-radius: 8px;">
                    <strong style="color: var(--primary-color);">📱 Smishing</strong>
                    <p style="font-size: 0.9rem; margin-top: 5px;">Phishing attempts sent via SMS or text messages.</p>
                </div>
            </div>
        </div>

        <div class="section-box">
            <h2 class="section-title">How to Spot a Phish</h2>
            <ul class="tip-list">
                <li class="tip-item">
                    <span class="tip-icon">✔️</span>
                    <span><strong>Check the Sender:</strong> Look for misspellings (e.g., @amaz0n.com vs @amazon.com).</span>
                </li>
                <li class="tip-item">
                    <span class="tip-icon">✔️</span>
                    <span><strong>Hover Links:</strong> Hover over buttons to see the <em>real</em> URL destination before clicking.</span>
                </li>
                <li class="tip-item">
                    <span class="tip-icon">✔️</span>
                    <span><strong>Urgency:</strong> Be skeptical of emails demanding "Immediate Action" or "Account Suspension".</span>
                </li>
                <li class="tip-item">
                    <span class="tip-icon">✔️</span>
                    <span><strong>Generic Greetings:</strong> Legitimate companies usually use your name, not "Dear Customer".</span>
                </li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 40px; margin-bottom: 60px;">
            <p style="margin-bottom: 20px;">Ready to test your skills?</p>
            <a href="{{ route('phishing.index') }}" class="btn btn-primary" style="padding: 12px 30px; font-size: 1.1rem;">Start Simulation</a>
            <br>
            <a href="/" style="display: inline-block; margin-top: 20px; color: var(--text-muted);">Back to Dashboard</a>
        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>