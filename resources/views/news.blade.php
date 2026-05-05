<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News & Resources | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .news-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .news-card { background: var(--bg-card); border: 1px solid var(--border-color); padding: 20px; border-radius: 12px; transition: 0.2s; }
        .news-card:hover { border-color: var(--primary-color); transform: translateY(-3px); }
        .news-date { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 10px; display: block; }
        .news-title { font-size: 1.2rem; font-weight: bold; margin-bottom: 10px; color: var(--text-main); }
        .news-snippet { font-size: 0.95rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 15px; }
        .resource-link { color: var(--primary-color); text-decoration: none; font-weight: bold; font-size: 0.9rem; }
        .resource-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container">
        
        <div style="text-align: center; margin: 3rem 0;">
            <h1>News & <span style="color: var(--primary-color);">Resources</span></h1>
            <p style="color: var(--text-muted);">Latest threats, patches, and security guides.</p>
        </div>

        <h2 style="border-bottom: 2px solid var(--border-color); padding-bottom: 10px; margin-bottom: 20px;">Latest Security Alerts</h2>

        <div class="news-grid">
            
            <div class="news-card">
                <span class="news-date">Dec 28, 2025</span>
                <h3 class="news-title">Zero-Day Vulnerability in Chrome</h3>
                <p class="news-snippet">
                    Google has released an urgent update to patch a high-severity zero-day exploit. Users are advised to update their browsers immediately.
                </p>
                <a href="#" class="resource-link">Read Advisory &rarr;</a>
            </div>

            <div class="news-card">
                <span class="news-date">Dec 20, 2025</span>
                <h3 class="news-title">New AI Phishing Tactics</h3>
                <p class="news-snippet">
                    Attackers are using generative AI to write convincing phishing emails without grammatical errors. Learn how to spot the new signs.
                </p>
                <a href="{{ route('learn.phishing') }}" class="resource-link">View our Guide &rarr;</a>
            </div>

            <div class="news-card">
                <span class="news-date">Nov 15, 2025</span>
                <h3 class="news-title">Password Managers Under Attack</h3>
                <p class="news-snippet">
                    A major password manager service reported a breach. If you use similar services, ensure your master password is strong and unique.
                </p>
                <a href="#" class="resource-link">Security Best Practices &rarr;</a>
            </div>

        </div>

        <h2 style="border-bottom: 2px solid var(--border-color); padding-bottom: 10px; margin-top: 50px; margin-bottom: 20px;">Useful Tools</h2>
        
        <div class="news-grid">
            <div class="news-card">
                <h3 class="news-title">🔐 Have I Been Pwned?</h3>
                <p class="news-snippet">Check if your email or phone number has been found in a data breach.</p>
                <a href="https://haveibeenpwned.com" target="_blank" class="resource-link">Check Now &rarr;</a>
            </div>
            <div class="news-card">
                <h3 class="news-title">🦠 VirusTotal</h3>
                <p class="news-snippet">Analyze suspicious files, domains, IPs, and URLs to detect malware.</p>
                <a href="https://www.virustotal.com" target="_blank" class="resource-link">Scan File &rarr;</a>
            </div>
            <div class="news-card">
                <h3 class="news-title">🛡️ CISA Alerts</h3>
                <p class="news-snippet">Official US Cybersecurity & Infrastructure Security Agency alerts.</p>
                <a href="https://www.cisa.gov/news-events/cybersecurity-advisories" target="_blank" class="resource-link">Visit CISA &rarr;</a>
            </div>
        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>