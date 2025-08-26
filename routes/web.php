<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/skills', [AdminController::class, 'skillsIndex'])->name('admin.skills.index');
    Route::get('/skills/create', [AdminController::class, 'createSkill'])->name('admin.skill.create');
    Route::patch('/approve/{user}', [AdminController::class, 'approve'])->name('admin.approve');
    Route::patch('/reject/{user}', [AdminController::class, 'reject'])->name('admin.reject');
    Route::get('/user/{user}', [AdminController::class, 'show'])->name('admin.user.show');
});

require __DIR__.'/auth.php';
