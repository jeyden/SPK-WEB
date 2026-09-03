<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Counselor\DashboardController as CounselorDashboardController;
use App\Http\Controllers\Counselor\CriteriaController;
use App\Http\Controllers\Counselor\MajorCriteriaController;
use App\Http\Controllers\Counselor\StudentAssessmentController;
use App\Http\Controllers\Counselor\CalculationController;
use App\Http\Controllers\Counselor\RegistrationPeriodController;
use App\Http\Controllers\Student\OnboardingController;
use App\Http\Controllers\Student\RiasecAssessmentController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\RecommendationController;
use App\Http\Controllers\Student\ArticleController as StudentArticleController;
use App\Http\Controllers\Student\CampusController as StudentCampusController;
use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// ==========================================
// 1. Landing Page (Public & Auto-Redirect)
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('counselor')) return redirect()->route('counselor.dashboard');
        if ($user->hasRole('student')) return redirect()->route('student.dashboard');
    }

    return view('landingpage');
})->name('landingpage');

// ==========================================
// 2. Guest Routes
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);

    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister']);

    // Lupa Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'update'])
        ->name('password.update');
});

// ==========================================
// 3. Authenticated Routes
// ==========================================
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // Dashboard Dispatcher
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('counselor')) {
            return redirect()->route('counselor.dashboard');
        }

        if ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        abort(403, 'Akses tidak sah.');
    })->name('dashboard');

    // ======================================
    // Profil Umum
    // ======================================
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // ======================================
    // Admin Module
    // ======================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

        Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');

        Route::resource('users', UserController::class);
        Route::resource('articles', AdminArticleController::class);

        Route::get('settings', [SystemSettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SystemSettingController::class, 'update'])->name('settings.update');
    });

    // ======================================
    // Guru BK Module
    // ======================================
    Route::middleware(['role:counselor'])->prefix('counselor')->name('counselor.')->group(function () {
        Route::get('/dashboard', [CounselorDashboardController::class, 'index'])->name('dashboard');

        // Kriteria Penilaian
        Route::get('/criteria', [CriteriaController::class, 'index'])->name('criteria.index');
        Route::get('/criteria/create', [CriteriaController::class, 'create'])->name('criteria.create');
        Route::get('/criteria/{id}/edit', [CriteriaController::class, 'edit'])->name('criteria.edit');

        // Profil Standar Jurusan
        Route::resource('majors', MajorCriteriaController::class);

        // Penilaian Siswa
        Route::get('/assessments', [StudentAssessmentController::class, 'index'])->name('assessments.index');
        Route::get('/assessments/import', [StudentAssessmentController::class, 'importForm'])->name('assessments.import.form');
        Route::get('/assessments/import/template', [StudentAssessmentController::class, 'downloadTemplate'])->name('assessments.import.template');
        Route::post('/assessments/import', [StudentAssessmentController::class, 'import'])->name('assessments.import');
        Route::get('/assessments/export', [StudentAssessmentController::class, 'export'])->name('assessments.export');

        Route::get('/assessments/{student}/assess', [StudentAssessmentController::class, 'assess'])->name('assessments.assess');
        Route::post('/assessments/{student}/store', [StudentAssessmentController::class, 'store'])->name('assessments.store');

        // Perhitungan & Perankingan SAW
        Route::get('/calculation', [CalculationController::class, 'index'])->name('calculation.index');
        Route::post('/calculation/process', [CalculationController::class, 'process'])->name('calculation.process');
        Route::get('/calculation/detail/{studentId}', [CalculationController::class, 'detail'])->name('calculation.detail');

        // Pengaturan, Laporan, & Cetak/PDF
        Route::post('/calculation/settings', [CalculationController::class, 'updateSettings'])->name('calculation.settings.update');
        Route::get('/calculation/print-single/{studentId}', [CalculationController::class, 'printSingle'])->name('calculation.print.single');
        Route::get('/calculation/export-pdf-single/{studentId}', [CalculationController::class, 'exportPdfSingle'])->name('calculation.pdf.single');
        Route::get('/calculation/report/{studentId}', [CalculationController::class, 'report'])->name('calculation.report');
        Route::get('/calculation/print-all', [CalculationController::class, 'print-all'])->name('calculation.print-all');
        Route::get('/calculation/export-pdf-all', [CalculationController::class, 'exportPdfAll'])->name('calculation.export-pdf-all');

        // Periode Pendaftaran
        Route::get('/registration-periods', [RegistrationPeriodController::class, 'index'])->name('registration-periods.index');
        Route::get('/registration-periods/create', [RegistrationPeriodController::class, 'create'])->name('registration-periods.create');
        Route::post('/registration-periods', [RegistrationPeriodController::class, 'store'])->name('registration-periods.store');
        Route::get('/registration-periods/{registrationPeriod}/edit', [RegistrationPeriodController::class, 'edit'])->name('registration-periods.edit');
        Route::put('/registration-periods/{registrationPeriod}', [RegistrationPeriodController::class, 'update'])->name('registration-periods.update');
        Route::patch('/registration-periods/{registrationPeriod}/open', [RegistrationPeriodController::class, 'open'])->name('registration-periods.open');
        Route::patch('/registration-periods/{registrationPeriod}/close', [RegistrationPeriodController::class, 'close'])->name('registration-periods.close');
        Route::delete('/registration-periods/{registrationPeriod}', [RegistrationPeriodController::class, 'destroy'])->name('registration-periods.destroy');

        // Pendaftar
        Route::get('/registration-periods/{registrationPeriod}/registrants', [RegistrationPeriodController::class, 'registrants'])->name('registration-periods.registrants');
        Route::get('/registration-periods/{registrationPeriod}/registrants/{student}', [RegistrationPeriodController::class, 'showRegistrant'])->name('registration-periods.registrants.show');
        Route::delete('/registration-periods/{registrationPeriod}/registrants/{student}', [RegistrationPeriodController::class, 'destroyRegistrant'])->name('registration-periods.registrants.destroy');
    });

    // ======================================
    // Siswa Module
    // ======================================
    Route::middleware(['role:student', 'student.onboarding'])->prefix('student')->name('student.')->group(function () {

        // Onboarding
        Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');

        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        // Data Diri
        Route::get('/profile-data', [StudentProfileController::class, 'index'])->name('profile.index');

        Route::middleware(['registration.open'])->group(function () {
            Route::get('/profile-data/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile-data', [StudentProfileController::class, 'update'])->name('profile.update');

            // Asesmen RIASEC
            Route::get('/riasec', [RiasecAssessmentController::class, 'index'])->name('riasec.index');
            Route::post('/riasec', [RiasecAssessmentController::class, 'store'])->name('riasec.store');
        });

        // ======================================
        // Rekomendasi Program Studi
        // ======================================
        Route::get('/recommendations', [RecommendationController::class, 'index'])
            ->name('recommendations.index');

        Route::get('/recommendations/{recommendation}/detail', [RecommendationController::class, 'detail'])
            ->name('recommendations.detail');

        // Artikel
        Route::get('/articles', [StudentArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/{slug}', [StudentArticleController::class, 'show'])->name('articles.show');
    });
});