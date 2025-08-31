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

// Temporary test route for notifications
Route::get('/test-notifications', function () {
    $user = Auth::user();
    if (!$user) {
        return 'No user logged in';
    }
    
    $unreadCount = \App\Http\Controllers\TradeController::getUnreadNotificationCount($user->id);
    $totalNotifications = \DB::table('user_notifications')->where('user_id', $user->id)->count();
    
    return "User ID: {$user->id}<br>Total notifications: {$totalNotifications}<br>Unread notifications: {$unreadCount}";
})->middleware('auth');

// Temporary test route to add a notification
Route::get('/add-test-notification', function () {
    $user = Auth::user();
    if (!$user) {
        return 'No user logged in';
    }
    
    \DB::table('user_notifications')->insert([
        'user_id' => $user->id,
        'type' => 'test_notification',
        'data' => json_encode(['message' => 'This is a test notification']),
        'read' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return "Test notification added for user {$user->id}. <a href='/test-notifications'>Check count</a>";
})->middleware('auth');

// Temporary test route to check trades status
Route::get('/check-trades', function () {
    $user = Auth::user();
    if (!$user) {
        return 'No user logged in';
    }
    
    $allTrades = \App\Models\Trade::count();
    $ongoingTrades = \App\Models\Trade::where('status', 'ongoing')->count();
    $userTrades = \App\Models\Trade::where('user_id', $user->id)->count();
    $userOngoingTrades = \App\Models\Trade::where('user_id', $user->id)->where('status', 'ongoing')->count();
    
    return "
    <h3>Trade Status Check</h3>
    <p><strong>Total trades in database:</strong> {$allTrades}</p>
    <p><strong>Total ongoing trades:</strong> {$ongoingTrades}</p>
    <p><strong>Your total trades:</strong> {$userTrades}</p>
    <p><strong>Your ongoing trades:</strong> {$userOngoingTrades}</p>
    <p><a href='/trades/ongoing'>Go to Ongoing Trades Page</a></p>
    <p><a href='/make-trade-ongoing'>Make First Trade Ongoing (for testing)</a></p>
    ";
})->middleware('auth');

// Temporary test route to make a trade ongoing
Route::get('/make-trade-ongoing', function () {
    $user = Auth::user();
    if (!$user) {
        return 'No user logged in';
    }
    
    $trade = \App\Models\Trade::where('user_id', $user->id)->first();
    if (!$trade) {
        return 'No trades found for this user. <a href="/trades/create">Create a trade first</a>';
    }
    
    $trade->update(['status' => 'ongoing']);
    
    return "Trade ID {$trade->id} is now ongoing! <a href='/trades/ongoing'>View Ongoing Trades</a>";
})->middleware('auth');

// Test route to check if chat system is working
Route::get('/test-chat-system', function () {
    return "
    <h2>Chat System Status</h2>
    <p><strong>Database Tables:</strong></p>
    <ul>
        <li>trade_messages: " . (\Schema::hasTable('trade_messages') ? '✅ Created' : '❌ Missing') . "</li>
        <li>trade_tasks: " . (\Schema::hasTable('trade_tasks') ? '✅ Created' : '❌ Missing') . "</li>
    </ul>
    <p><strong>Models:</strong></p>
    <ul>
        <li>TradeMessage: " . (class_exists('App\Models\TradeMessage') ? '✅ Loaded' : '❌ Missing') . "</li>
        <li>TradeTask: " . (class_exists('App\Models\TradeTask') ? '✅ Loaded' : '❌ Missing') . "</li>
    </ul>
    <p><strong>Routes:</strong></p>
    <ul>
        <li>Chat Show: " . (Route::has('chat.show') ? '✅ Registered' : '❌ Missing') . "</li>
        <li>Send Message: " . (Route::has('chat.send-message') ? '✅ Registered' : '❌ Missing') . "</li>
        <li>Create Task: " . (Route::has('chat.create-task') ? '✅ Registered' : '❌ Missing') . "</li>
        <li>Toggle Task: " . (Route::has('chat.toggle-task') ? '✅ Registered' : '❌ Missing') . "</li>
    </ul>
    <p><a href='/trades/ongoing'>Go to Ongoing Trades</a></p>
    ";
});

// Test route to add a sample message
Route::get('/add-test-message/{trade_id}', function ($trade_id) {
    $trade = \App\Models\Trade::find($trade_id);
    if (!$trade) {
        return "Trade not found!";
    }
    
    $message = \App\Models\TradeMessage::create([
        'trade_id' => $trade_id,
        'sender_id' => \Auth::id(),
        'message' => 'Test message at ' . now()->format('H:i:s')
    ]);
    
    return "Test message added! Message ID: " . $message->id . "<br><a href='/chat/{$trade_id}'>Go to Chat</a>";
})->middleware('auth');

// Debug route to check chat messages
Route::get('/debug-chat/{trade_id}', function ($trade_id) {
    $trade = \App\Models\Trade::find($trade_id);
    if (!$trade) {
        return "Trade not found!";
    }
    
    $messages = \App\Models\TradeMessage::where('trade_id', $trade_id)
        ->with('sender')
        ->orderBy('created_at', 'asc')
        ->get();
    
    $output = "<h2>Debug Chat for Trade #{$trade_id}</h2>";
    $output .= "<p><strong>Total Messages:</strong> " . $messages->count() . "</p>";
    
    if ($messages->count() > 0) {
        $output .= "<h3>Messages:</h3>";
        foreach ($messages as $message) {
            $output .= "<div style='border: 1px solid #ccc; margin: 10px 0; padding: 10px;'>";
            $output .= "<strong>ID:</strong> " . $message->id . "<br>";
            $output .= "<strong>Sender:</strong> " . $message->sender->firstname . " " . $message->sender->lastname . "<br>";
            $output .= "<strong>Message:</strong> " . $message->message . "<br>";
            $output .= "<strong>Time:</strong> " . $message->created_at . "<br>";
            $output .= "</div>";
        }
    } else {
        $output .= "<p>No messages found for this trade.</p>";
    }
    
    $output .= "<p><a href='/chat/{$trade_id}'>Go to Chat</a> | <a href='/add-test-message/{$trade_id}'>Add Test Message</a></p>";
    
    return $output;
})->middleware('auth');

// Test route to manually send a message (for debugging)
Route::post('/test-send-message/{trade_id}', function ($trade_id, \Illuminate\Http\Request $request) {
    $trade = \App\Models\Trade::find($trade_id);
    if (!$trade) {
        return response()->json(['error' => 'Trade not found'], 404);
    }
    
    $user = \Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    
    $message = $request->input('message');
    if (!$message) {
        return response()->json(['error' => 'Message is required'], 400);
    }
    
    try {
        $newMessage = \App\Models\TradeMessage::create([
            'trade_id' => $trade_id,
            'sender_id' => $user->id,
            'message' => $message
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $newMessage,
            'debug' => [
                'trade_id' => $trade_id,
                'user_id' => $user->id,
                'message_text' => $message
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to save message',
            'exception' => $e->getMessage()
        ], 500);
    }
})->middleware('auth');

// Simple endpoint to get latest messages
Route::get('/chat/{trade_id}/messages', function ($trade_id) {
    $trade = \App\Models\Trade::find($trade_id);
    if (!$trade) {
        return response()->json(['error' => 'Trade not found'], 404);
    }
    
    $user = \Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    
    // Check if user is part of this trade
    if ($trade->user_id !== $user->id && 
        !$trade->requests()->where('requester_id', $user->id)->where('status', 'accepted')->exists()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    $messages = $trade->messages()->with('sender')->orderBy('created_at', 'asc')->get();
    
    return response()->json([
        'success' => true,
        'messages' => $messages,
        'count' => $messages->count()
    ]);
})->middleware('auth');

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
