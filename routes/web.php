<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQuestionController;
use App\Models\SimulationAttempt;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home Dashboard
Route::get('/', function () {
    $stats = null;
    $user = Auth::user();
    if ($user) {
        $attempts = SimulationAttempt::where('user_id', $user->id)->get();
        $totalRuns = $attempts->count();
        $stats = [
            'total_runs' => $totalRuns,
            'avg_score' => $totalRuns > 0 ? round($attempts->avg('score')) : 0,
            'last_active' => $attempts->last() ? $attempts->last()->created_at->diffForHumans() : 'Never'
        ];
    }
    return view('welcome', ['user' => $user, 'stats' => $stats]);
})->name('home');

// Auth: Login
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Auth: Register
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// News & Resources
Route::get('/news', function () { return view('news'); })->name('news');

// Static Learning Content
Route::get('/learn/phishing', function () { return view('learn.phishing'); })->name('learn.phishing');
Route::get('/learn/malware', function () { return view('learn.malware'); })->name('learn.malware');
Route::get('/learn/spam', function () { return view('learn.spam'); })->name('learn.spam');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Must be logged in)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Phishing Module
    Route::get('/phishing', [SimulationController::class, 'index'])->name('phishing.index');
    Route::post('/phishing/update', [SimulationController::class, 'update'])->name('phishing.update');
    Route::post('/phishing/submit', [SimulationController::class, 'submitAttempt'])->name('phishing.submit');

    // Malware Module (Now using the new separate logic)
    Route::get('/malware', [SimulationController::class, 'showMalware'])->name('malware');
    Route::post('/malware/update', [SimulationController::class, 'updateMalware'])->name('malware.update');
    Route::post('/malware/submit', [SimulationController::class, 'submitMalwareAttempt'])->name('malware.submit');

    // Spam Module (Static for now until logic is added)
    Route::get('/spam', function () { return view('spam'); })->name('spam');
    // Inside your auth middleware group
Route::get('/spam', [\App\Http\Controllers\SimulationController::class, 'showSpam'])->name('spam');
Route::post('/spam/update', [\App\Http\Controllers\SimulationController::class, 'updateSpam']);
Route::post('/spam/submit', [\App\Http\Controllers\SimulationController::class, 'submitSpam']);
Route::get('/certificate', [ProfileController::class, 'showCertificate'])->name('certificate');

});


/*
|--------------------------------------------------------------------------
| Admin Routes (Login + Admin Check Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Basic Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
    Route::delete('/admin/attempt/{id}', [AdminController::class, 'deleteAttempt'])->name('admin.deleteAttempt');
    Route::resource('questions', AdminQuestionController::class);
    

    // Question Management (Phishing)
    Route::resource('/admin/questions', AdminQuestionController::class, [
        'names' => [
            'index'   => 'admin.questions.index',
            'create'  => 'admin.questions.create',
            'store'   => 'admin.questions.store',
            'edit'    => 'admin.questions.edit',
            'update'  => 'admin.questions.update',
            'destroy' => 'admin.questions.destroy',
        ]
    ]);
});


/*
|--------------------------------------------------------------------------
| Utility / Maintenance Routes (Safe to delete before final submission)
|--------------------------------------------------------------------------
*/
Route::get('/repair-all', function () {
    // Clear and restore data logic moved to QuestionSeeder.php for safety
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'QuestionSeeder']);
    return "<h1>✅ Database Restored from Seeder!</h1><p><a href='/'>Back Home</a></p>";
});

