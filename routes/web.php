<?php
use App\Http\Controllers\CategoryController;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

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



// Login and Registration routes (publicly accessible)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Logout should be protected and requires authentication
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::resource('categories', CategoryController::class);
        Route::post('questions/store/{categoryId}', [QuestionController::class, 'store'])->name('admin.questions.store');
    });
});

