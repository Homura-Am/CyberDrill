<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learn: Spam | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Reusing the same styles for consistency */
        .learn-container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .hero-section { text-align: center; margin-bottom: 40px; }
        .hero-icon { font-size: 4rem; margin-bottom: 10px; display: inline-block; animation: shake 2s ease-in-out infinite; }
        .section-box { background: var(--bg-card); padding: 30px; border-radius: 12px; margin-bottom: 30px; border: 1px solid var(--border-color); }
        .section-title { color: var(--primary-color); font-size: 1.5rem; margin-bottom: 15px; font-weight: bold; }
        .content-text { line-height: 1.8; color: var(--text-main); font-size: 1.05rem; }
        .tip-list { list-style: none; padding: 0; }
        .tip-item { display: flex; align-items: start; margin-bottom: 15px; }
        .tip-icon { color: #f59e0b; margin-right: 15px; font-size: 1.2rem; } /* Orange/Yellow for Spam */
        
        @keyframes shake { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(5deg); } 75% { transform: rotate(-5deg); } }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container learn-container">
        
        <div class="hero-section">
            <div class="hero-icon">🗑️</div>
            <h1>Spam vs. Phishing</h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Understanding the noise in your inbox.</p>
        </div>

        <div class="section-box">
            <h2 class="section-title">What is Spam?</h2>
            <p class="content-text">
                Spam is unsolicited junk email sent in bulk to a large list of recipients. Unlike phishing, spam is not always malicious—often it is just annoying advertising (pharmaceuticals, weight loss, gambling). However, spam can clog your inbox, reduce productivity, and sometimes carry malware.
            </p>
        </div>

        <div class="section-box">
            <h2 class="section-title">Spam vs. Phishing: The Difference</h2>
            <table style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 10px; color: var(--text-muted);">Feature</th>
                        <th style="padding: 10px; color: var(--primary-color);">Spam 🗑️</th>
                        <th style="padding: 10px; color: #ef4444;">Phishing 🎣</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 10px;"><strong>Goal</strong></td>
                        <td style="padding: 10px;">Sell you something</td>
                        <td style="padding: 10px;">Steal your credentials</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 10px;"><strong>Targeting</strong></td>
                        <td style="padding: 10px;">Broad / Random</td>
                        <td style="padding: 10px;">Targeted / Specific</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 10px;"><strong>Danger Level</strong></td>
                        <td style="padding: 10px;">Low (Annoyance)</td>
                        <td style="padding: 10px;">High (Theft/Breach)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section-box">
            <h2 class="section-title">How to Reduce Spam</h2>
            <ul class="tip-list">
                <li class="tip-item">
                    <span class="tip-icon">⚠️</span>
                    <span><strong>Don't Reply:</strong> Replying to spam tells the sender your email address is active, which leads to <em>more</em> spam.</span>
                </li>
                <li class="tip-item">
                    <span class="tip-icon">⚠️</span>
                    <span><strong>Use 'Unsubscribe' with Caution:</strong> Only use the "Unsubscribe" link if the email is from a legitimate company (like a newsletter you signed up for). If it's sketchy spam, clicking unsubscribe confirms your email is valid.</span>
                </li>
                <li class="tip-item">
                    <span class="tip-icon">⚠️</span>
                    <span><strong>Mark as Spam:</strong> Use your email provider's "Report Spam" button to train their filters.</span>
                </li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 40px; margin-bottom: 60px;">
            <a href="/" style="color: var(--text-muted);">Back to Dashboard</a>
        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>