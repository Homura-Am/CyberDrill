<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up | CyberDrill</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    @include('partials.navbar')

    <div class="container auth-container">
        
        <div class="auth-card">
            <div style="font-size: 3rem; margin-bottom: 10px;">🛡️</div>
            
            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Start your cybersecurity training today.</p>

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 6px; margin-bottom: 15px; text-align: left; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="name@company.com" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    Create Account
                </button>
            </form>

            <div style="margin-top: 20px; font-size: 0.9rem; color: var(--text-muted);">
                Already have an account? <a href="{{ route('login') }}" class="auth-link">Log In</a>
            </div>
        </div>
    </div>

    <script>
        if (localStorage.getItem('theme') === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    </script>
</body>
</html>