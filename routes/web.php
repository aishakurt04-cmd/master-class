<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CraftController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterClassController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Главная страница
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Аутентификация
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Виды творчества (доступно всем)
|--------------------------------------------------------------------------
*/
Route::get('/craft/{craft}', [CraftController::class, 'show'])->name('craft.show');

/*
|--------------------------------------------------------------------------
| Мастер-классы (только для авторизованных ведущих)
|--------------------------------------------------------------------------
*/
Route::prefix('master-class')->name('master-class.')->middleware(['auth', 'leader'])->group(function () {
    Route::get('/create', [MasterClassController::class, 'create'])->name('create');
    Route::post('/', [MasterClassController::class, 'store'])->name('store');
    Route::get('/{masterClass}/edit', [MasterClassController::class, 'edit'])->name('edit');
    Route::put('/{masterClass}', [MasterClassController::class, 'update'])->name('update');
    Route::get('/available-slots', [MasterClassController::class, 'getAvailableSlots'])->name('available-slots');
});

/*
|--------------------------------------------------------------------------
| Личный кабинет ведущего (только для авторизованных ведущих)
|--------------------------------------------------------------------------
*/
Route::get('/cabinet', [CabinetController::class, 'index'])
    ->middleware(['auth', 'leader'])
    ->name('cabinet');

/*
|--------------------------------------------------------------------------
| Запись на мастер-класс (только для авторизованных пользователей)
|--------------------------------------------------------------------------
*/
Route::prefix('registration')->name('registration.')->middleware('auth')->group(function () {
    Route::get('/{masterClass}/create', [RegistrationController::class, 'create'])->name('create');
    Route::post('/{masterClass}', [RegistrationController::class, 'store'])->name('store');
});
