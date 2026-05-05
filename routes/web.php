<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Models\SimulationAttempt;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQuestionController;

Route::middleware(['auth'])->group(function () {
    // ... existing admin routes ...
    
    // QUESTION MANAGEMENT
    Route::get('/admin/questions', [AdminQuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/admin/questions/create', [AdminQuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/admin/questions', [AdminQuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/admin/questions/{id}/edit', [AdminQuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/admin/questions/{id}', [AdminQuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/admin/questions/{id}', [AdminQuestionController::class, 'destroy'])->name('admin.questions.destroy');
});

Route::get('/', function () {
    $stats = null;
    $user = Auth::user();

    if ($user) {
        // Calculate basic stats for the widget
        $attempts = SimulationAttempt::where('user_id', $user->id)
            ->where('module', 'phishing')
            ->get();
            
        $totalRuns = $attempts->count();
        $avgScore = $totalRuns > 0 ? round($attempts->avg('score')) : 0;
        
        $stats = [
            'total_runs' => $totalRuns,
            'avg_score' => $avgScore,
            'last_active' => $attempts->last() ? $attempts->last()->created_at->diffForHumans() : 'Never'
        ];
    }

    return view('welcome', ['user' => $user, 'stats' => $stats]);
})->name('home');

// ... (Keep your other routes) ...


Route::get('/phishing', function () {
    return view('phishing');
});
Route::get('/malware', function () {
    return view('malware');
});
Route::get('/spam', function () {
    return view('spam');
});


Route::get('/login', function () {
    return view('auth.login'); // <--- Points to resources/views/auth/login.blade.php
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Listens for the login form submission
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Handles logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Show the page (loads saved data)
Route::get('/phishing', [SimulationController::class, 'index'])->name('phishing.index');

// Save data (via JavaScript)
Route::post('/phishing/update', [SimulationController::class, 'update'])->name('phishing.update');

Route::post('/phishing/reset', [App\Http\Controllers\SimulationController::class, 'reset'])->name('phishing.reset');

// --- LEARNING MODULES ---
Route::get('/learn/phishing', function () {
    return view('learn.phishing');
})->name('learn.phishing');

Route::get('/learn/malware', function () {
    return view('learn.malware');
})->name('learn.malware');

Route::get('/learn/spam', function () {
    return view('learn.spam');
})->name('learn.spam');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // ADD THIS MISSING LINE:
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});


// --- NEWS & RESOURCES ---
Route::get('/news', function () {
    return view('news');
})->name('news');

Route::post('/phishing/submit', [App\Http\Controllers\SimulationController::class, 'submitAttempt'])->name('phishing.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // NEW ROUTES
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
    Route::delete('/admin/attempt/{id}', [AdminController::class, 'deleteAttempt'])->name('admin.deleteAttempt');
    Route::get('/admin/questions', [AdminQuestionController::class, 'index'])->name('admin.questions.index');
    Route::get('/admin/questions/create', [AdminQuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/admin/questions', [AdminQuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/admin/questions/{id}/edit', [AdminQuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/admin/questions/{id}', [AdminQuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/admin/questions/{id}', [AdminQuestionController::class, 'destroy'])->name('admin.questions.destroy');
});

// --- TEMPORARY REPAIR ROUTE ---
Route::get('/repair-simulation', function () {
    // 1. Clear bad data
    \App\Models\Question::truncate();

    // 2. Insert fresh data
    $questions = [
        [
            'module' => 'phishing',
            'key' => 'ceo_fraud',
            'title' => 'Urgent Wire Transfer',
            'type' => 'email',
            'sender_name' => 'CEO - John Smith',
            'sender_email' => 'ceo-urgent@company-mail.net',
            'subject' => 'URGENT: Client Payment Required',
            'body' => 'Hi,<br><br>I need you to process an urgent wire transfer immediately.<br><br>Sent from my iPad',
            'options' => [
                ['text' => 'Process Transfer', 'result' => 'incorrect', 'feedback' => 'Always verify urgent money requests via phone.'],
                ['text' => 'Reply asking details', 'result' => 'neutral', 'feedback' => 'Replying confirms your email to spammers.'],
                ['text' => 'Report Phishing', 'result' => 'correct', 'feedback' => 'Correct. The domain @company-mail.net is fake.']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'fake_hr',
            'title' => 'HR Policy Update',
            'type' => 'email',
            'sender_name' => 'Human Resources',
            'sender_email' => 'hr-notifications@company-secure-portal.xyz',
            'subject' => 'Action Required: New Vacation Policy',
            'body' => 'Please login below.<br><br><a href="#" class="fake-link" title="http://hr-portal-secure.xyz/login">Click here to login</a>',
            'options' => [
                ['text' => 'Login to Portal', 'result' => 'incorrect', 'feedback' => 'Credential harvesting site (.xyz domain).'],
                ['text' => 'Report Phishing', 'result' => 'correct', 'feedback' => 'Good catch. External URL.'],
                ['text' => 'Ignore Email', 'result' => 'neutral', 'feedback' => 'Reporting helps protect others.']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'suspicious_login',
            'title' => 'New Login Alert',
            'type' => 'email',
            'sender_name' => 'Security Team',
            'sender_email' => 'alert@micr0soft-security.net',
            'subject' => 'Security Alert: Login from Russia',
            'body' => 'We detected a sign-in.<br><br><button class="fake-btn" title="http://micr0soft-security.net">Secure My Account</button>',
            'options' => [
                ['text' => 'Click Button', 'result' => 'incorrect', 'feedback' => 'Fake domain (micr0soft). Login to official site manually.'],
                ['text' => 'Check Settings', 'result' => 'correct', 'feedback' => 'Perfect. Navigate manually.'],
                ['text' => 'Reply', 'result' => 'neutral', 'feedback' => 'Automated emails are no-reply.']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'zoom_invite',
            'title' => 'Zoom Meeting Invite',
            'type' => 'email',
            'sender_name' => 'Zoom',
            'sender_email' => 'no-reply@zoom.us',
            'subject' => 'Meeting Invitation',
            'body' => 'You have been invited to a meeting.<br><br><a href="#" class="fake-link" title="https://zoom.us/j/998273645">Join Zoom Meeting</a>',
            'options' => [
                ['text' => 'Join Meeting', 'result' => 'correct', 'feedback' => 'Correct! This is a legitimate email from zoom.us.'],
                ['text' => 'Report Phishing', 'result' => 'neutral', 'feedback' => 'This email is safe.'],
                ['text' => 'Delete Email', 'result' => 'incorrect', 'feedback' => 'You missed a real meeting!']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'it_maintenance',
            'title' => 'System Maintenance',
            'type' => 'email',
            'sender_name' => 'IT Support',
            'sender_email' => 'support@company.com',
            'subject' => 'Scheduled Downtime',
            'body' => 'Systems offline Saturday.<br><br>Status: <a href="#" class="fake-link" title="https://status.company.com">status.company.com</a>',
            'options' => [
                ['text' => 'Mark Read', 'result' => 'correct', 'feedback' => 'Correct. Internal announcement.'],
                ['text' => 'Report Phishing', 'result' => 'neutral', 'feedback' => 'This is from your own IT team.'],
                ['text' => 'Reply', 'result' => 'incorrect', 'feedback' => 'Don\'t reply to mass announcements.']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'password_reset',
            'title' => 'Password Expiry SMS',
            'type' => 'sms',
            'sender_name' => 'IT-Support',
            'sender_email' => null,
            'subject' => null,
            'body' => 'Password expires in 2h. Reset: <br><span class="imessage-link" title="http://bit.ly/reset">http://bit.ly/reset</span>',
            'options' => [
                ['text' => 'Click Link', 'result' => 'incorrect', 'feedback' => 'IT never uses bit.ly for passwords.'],
                ['text' => 'Report Spam', 'result' => 'correct', 'feedback' => 'Correct.'],
                ['text' => 'Reply STOP', 'result' => 'neutral', 'feedback' => 'Confirms your number is active.']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'bank_fraud',
            'title' => 'Bank Fraud Alert',
            'type' => 'sms',
            'sender_name' => 'BankAlert',
            'sender_email' => null,
            'subject' => null,
            'body' => 'Charge of $4,299? <br>Cancel: <span class="imessage-link" title="http://secure-bank.net">http://secure-bank.net</span>',
            'options' => [
                ['text' => 'Click Cancel', 'result' => 'incorrect', 'feedback' => 'Phishing site.'],
                ['text' => 'Reply NO', 'result' => 'neutral', 'feedback' => 'Invites scam calls.'],
                ['text' => 'Login App', 'result' => 'correct', 'feedback' => 'Smart move.']
            ]
        ],
        [
            'module' => 'phishing',
            'key' => 'subscription_fail',
            'title' => 'Subscription Suspended',
            'type' => 'sms',
            'sender_name' => 'NetfIix',
            'sender_email' => null,
            'subject' => null,
            'body' => 'Payment declined. <br><span class="imessage-link" title="http://netflix-payment.com">http://netflix-payment.com</span>',
            'options' => [
                ['text' => 'Click Link', 'result' => 'incorrect', 'feedback' => 'Fake URL and Sender Name.'],
                ['text' => 'Go to App', 'result' => 'correct', 'feedback' => 'Correct.'],
                ['text' => 'Reply Help', 'result' => 'neutral', 'feedback' => 'Confirms number.']
            ]
        ]
    ];

    foreach ($questions as $q) {
        \App\Models\Question::create($q);
    }

    return "<h1>✅ REPAIR COMPLETE</h1><p>8 Scenarios have been inserted.</p><p><a href='/phishing'>Go Back to Simulation</a></p>";
});