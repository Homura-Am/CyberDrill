<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @include('partials.navbar')

    <div class="container auth-container">
        
        <div class="auth-card">
            <div style="font-size: 3rem; margin-bottom: 10px;">🔐</div>
            
            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Enter your credentials to access the simulation.</p>

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: left; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="name@company.com" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between;">
                        <label for="password" class="form-label">Password</label>
                        @if (Route::has('password.request'))
                            <a class="auth-link" href="{{ route('password.request') }}">Forgot?</a>
                        @endif
                    </div>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" id="remember_me" name="remember" style="accent-color: var(--primary-color);">
                    <label for="remember_me" style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    Log In
                </button>
            </form>

            <div style="margin-top: 20px; font-size: 0.9rem; color: var(--text-muted);">
                Don't have an account? <a href="{{ route('register') }}" class="auth-link">Sign Up</a>
            </div>
        </div>
    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>