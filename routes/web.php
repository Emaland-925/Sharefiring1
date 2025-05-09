<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\QuizController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Language switcher
Route::get('/language/{lang}', [LanguageController::class, 'switchLang'])->name('language.switch');

// Authentication routes
Auth::routes();

// Company registration
Route::get('/register/company', [CompanyController::class, 'register'])->name('register.company');
Route::post('/register/company', [CompanyController::class, 'store'])->name('register.company.store');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');
    
    // Leaderboard
    Route::get('/leaderboard', [DashboardController::class, 'leaderboard'])->name('leaderboard');
    
    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{id}/approve', [CourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{id}/reject', [CourseController::class, 'reject'])->name('courses.reject');
    
    // Lessons
    Route::get('/courses/{courseId}/lessons/create', [CourseController::class, 'addLesson'])->name('lessons.create');
    Route::post('/courses/{courseId}/lessons', [CourseController::class, 'storeLesson'])->name('lessons.store');
    Route::get('/courses/{courseId}/lessons/{lessonId}/edit', [CourseController::class, 'editLesson'])->name('lessons.edit');
    Route::put('/courses/{courseId}/lessons/{lessonId}', [CourseController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/courses/{courseId}/lessons/{lessonId}', [CourseController::class, 'destroyLesson'])->name('lessons.destroy');
    
    // Quizzes
    Route::get('/courses/{courseId}/quizzes/create', [CourseController::class, 'addQuiz'])->name('quizzes.create');
    Route::post('/courses/{courseId}/quizzes', [CourseController::class, 'storeQuiz'])->name('quizzes.store');
    Route::get('/quizzes/{id}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{id}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{id}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('/quizzes/{id}/take', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{id}/results', [QuizController::class, 'results'])->name('quizzes.results');
    
    // Questions
    Route::get('/quizzes/{quizId}/questions/create', [QuizController::class, 'addQuestion'])->name('questions.create');
    Route::post('/quizzes/{quizId}/questions', [QuizController::class, 'storeQuestion'])->name('questions.store');
    Route::get('/quizzes/{quizId}/questions/{questionId}/edit', [QuizController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/quizzes/{quizId}/questions/{questionId}', [QuizController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/quizzes/{quizId}/questions/{questionId}', [QuizController::class, 'destroyQuestion'])->name('questions.destroy');
    
    // Company routes
    Route::middleware(['company'])->group(function () {
        Route::get('/company/dashboard', [CompanyController::class, 'dashboard'])->name('company.dashboard');
        Route::get('/company/settings', [CompanyController::class, 'settings'])->name('company.settings');
        Route::put('/company/settings', [CompanyController::class, 'update'])->name('company.update');
        Route::get('/company/employees', [CompanyController::class, 'employees'])->name('company.employees');
        Route::get('/company/employees/create', [CompanyController::class, 'addEmployee'])->name('company.employees.create');
        Route::post('/company/employees', [CompanyController::class, 'storeEmployee'])->name('company.employees.store');
    });
    
    // Admin routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::get('/admin/companies', [AdminController::class, 'companies'])->name('admin.companies');
        Route::get('/admin/companies/create', [AdminController::class, 'createCompany'])->name('admin.companies.create');
        Route::post('/admin/companies', [AdminController::class, 'storeCompany'])->name('admin.companies.store');
        Route::get('/admin/companies/{id}/edit', [AdminController::class, 'editCompany'])->name('admin.companies.edit');
        Route::put('/admin/companies/{id}', [AdminController::class, 'updateCompany'])->name('admin.companies.update');
        Route::delete('/admin/companies/{id}', [AdminController::class, 'destroyCompany'])->name('admin.companies.destroy');
        Route::get('/admin/courses', [AdminController::class, 'courses'])->name('admin.courses');
        Route::get('/admin/courses/pending', [AdminController::class, 'pendingCourses'])->name('admin.courses.pending');
    });
});