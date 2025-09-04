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

    // Trades (user dashboard area)
    Route::get('/trades/create', [\App\Http\Controllers\TradeController::class, 'create'])->name('trades.create');
    Route::post('/trades', [\App\Http\Controllers\TradeController::class, 'store'])->name('trades.store');
    Route::get('/trades/matches', [\App\Http\Controllers\TradeController::class, 'matches'])->name('trades.matches');
    Route::get('/trades/requests', [\App\Http\Controllers\TradeController::class, 'requests'])->name('trades.requests');
    Route::get('/trades/ongoing', [\App\Http\Controllers\TradeController::class, 'ongoing'])->name('trades.ongoing');
    Route::get('/trades/notifications', [\App\Http\Controllers\TradeController::class, 'notify'])->name('trades.notifications');
    
    // Trade request actions
    Route::post('/trades/{trade}/request', [\App\Http\Controllers\TradeController::class, 'requestTrade'])->name('trades.request');
    Route::post('/trade-requests/{tradeRequest}/respond', [\App\Http\Controllers\TradeController::class, 'respondToRequest'])->name('trades.respond');
    
    // Notification actions
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\TradeController::class, 'markNotificationAsRead'])->name('trades.mark-read');
    
    // Chat routes
    Route::get('/chat/{trade}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::get('/chat/{trade}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{trade}/message', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::post('/chat/{trade}/task', [\App\Http\Controllers\ChatController::class, 'createTask'])->name('chat.create-task');
    Route::patch('/chat/task/{task}/toggle', [\App\Http\Controllers\ChatController::class, 'toggleTask'])->name('chat.toggle-task');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::get('/skills', [AdminController::class, 'skillsIndex'])->name('admin.skills.index');
    Route::get('/skills/create', [AdminController::class, 'createSkill'])->name('admin.skill.create');
    Route::post('/skills', [AdminController::class, 'storeSkill'])->name('admin.skill.store');
    Route::delete('/skills/{skill}', [AdminController::class, 'deleteSkill'])->name('admin.skill.delete');
    Route::patch('/approve/{user}', [AdminController::class, 'approve'])->name('admin.approve');
    Route::patch('/reject/{user}', [AdminController::class, 'reject'])->name('admin.reject');
    Route::get('/user/{user}', [AdminController::class, 'show'])->name('admin.user.show');
});

require __DIR__.'/auth.php';
