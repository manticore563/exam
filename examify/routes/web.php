<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Define the routes for Examify, optimized for shared hosting and responsive design.
|
*/

// Installer route (runs only if not installed)
Route::get('/install', function () {
    if (!file_exists(base_path('.env'))) {
        return redirect('/install/index.php');
    }
    return redirect('/');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



Route::get('/test-middleware', function () {
    $middleware = app('router')->getMiddleware();
    dd($middleware);
});


// Protected routes (require login)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route(auth()->user()->isAdmin() ? 'exams.index' : 'student.exams');
    })->name('home');

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
        Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
        Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
        Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
        
        Route::get('/questions/create/{exam}', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::get('/questions/import', [QuestionController::class, 'showImportForm'])->name('questions.import');
        Route::post('/questions/import', [QuestionController::class, 'import'])->name('questions.import.store');
        
        Route::get('/results', [ResultController::class, 'index'])->name('results.index');
        
        Route::get('/admin/reset-password', [App\Http\Controllers\Admin\ResetPasswordController::class, 'index'])->name('admin.reset-password');
        Route::post('/admin/reset-password', [App\Http\Controllers\Admin\ResetPasswordController::class, 'reset'])->name('admin.reset-password.store');
    });

    // Student routes
    Route::middleware('role:student')->group(function () {
        Route::get('/student/exams', [ExamController::class, 'studentIndex'])->name('student.exams');
        Route::get('/student/exams/{exam}', [ExamController::class, 'takeExam'])->name('student.exam.take');
        Route::post('/student/exams/{exam}/submit', [ExamController::class, 'submitExam'])->name('student.exam.submit');
        Route::get('/student/results', [ResultController::class, 'studentIndex'])->name('student.results');
    });
});

?>