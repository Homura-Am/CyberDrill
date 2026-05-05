<nav class="navbar">
    <div class="container nav-container">
        <a href="/" class="nav-brand">
            CYBER<span style="color: var(--primary-color);">DRILL</span>
        </a>

        <div class="nav-links">
            <a href="{{ route('news') }}" class="nav-item">📰 News & Resources</a>
        </div>

        <div class="nav-auth">
            @auth
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="nav-item" style="color: #f59e0b; margin-right: 20px; font-weight: bold;">
                        ⚡ Admin Panel
                    </a>
                @endif
                <a href="{{ route('profile') }}" class="btn btn-outline" style="padding: 6px 15px; font-size: 0.9rem; margin-right: 10px; border-color: var(--border-color); color: var(--text-main); text-transform: capitalize;">
                    👋 Hi, {{ explode(' ', Auth::user()->name)[0] }}
                </a>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="padding: 6px 15px; font-size: 0.9rem;">
                        Log Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-item" style="margin-right: 20px;">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 8px 20px;">Sign Up</a>
            @endauth

            <button id="theme-toggle" onclick="toggleTheme()" class="theme-btn" title="Toggle Theme">
                🌙
            </button>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background-color: var(--bg-card);
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 0;
        position: sticky; top: 0; z-index: 100;
    }
    .nav-container { display: flex; justify-content: space-between; align-items: center; }
    .nav-brand { font-size: 1.5rem; font-weight: 900; color: var(--text-main); text-decoration: none; margin-right: 30px; }
    .nav-links { flex-grow: 1; display: flex; gap: 20px; }
    .nav-item { color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.2s; }
    .nav-item:hover { color: var(--primary-color); }
    .nav-auth { display: flex; align-items: center; }
    .theme-btn { background: none; border: none; font-size: 1.2rem; cursor: pointer; margin-left: 15px; transition: transform 0.2s; }
    .theme-btn:hover { transform: scale(1.1); }
</style>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        document.getElementById('theme-toggle').innerText = next === 'dark' ? '☀️' : '🌙';
    }
    if(localStorage.getItem('theme') === 'dark') document.getElementById('theme-toggle').innerText = '☀️';
</script>