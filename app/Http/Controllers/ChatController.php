<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\TradeMessage;
use App\Models\TradeTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatController extends Controller
{
    public function show(Trade $trade)
    {
        $user = Auth::user();
        
        // Check if user is part of this trade
        if ($trade->user_id !== $user->id && 
            !$trade->requests()->where('requester_id', $user->id)->where('status', 'accepted')->exists()) {
            return redirect()->back()->with('error', 'You are not authorized to view this trade chat.');
        }

        // Get the other user (trade partner)
        $partner = $trade->user_id === $user->id 
            ? $trade->requests()->where('status', 'accepted')->first()->requester
            : $trade->user;

        // Get messages
        $messages = $trade->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        // Get tasks
        $myTasks = $trade->tasks()->where('assigned_to', $user->id)->get();
        $partnerTasks = $trade->tasks()->where('assigned_to', $partner->id)->get();

        // Calculate progress
        $myProgress = $myTasks->count() > 0 ? ($myTasks->where('completed', true)->count() / $myTasks->count()) * 100 : 0;
        $partnerProgress = $partnerTasks->count() > 0 ? ($partnerTasks->where('completed', true)->count() / $partnerTasks->count()) * 100 : 0;

        return view('chat.session', compact('trade', 'partner', 'messages', 'myTasks', 'partnerTasks', 'myProgress', 'partnerProgress'));
    }

    public function sendMessage(Request $request, Trade $trade)
    {
        $user = Auth::user();
        
        // Check if user is part of this trade
        if ($trade->user_id !== $user->id && 
            !$trade->requests()->where('requester_id', $user->id)->where('status', 'accepted')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = $trade->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message
        ]);

        $message->load('sender');

        // Broadcast to Pusher
        $this->broadcastMessage($trade->id, $message);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function createTask(Request $request, Trade $trade)
    {
        $user = Auth::user();
        
        // Check if user is part of this trade
        if ($trade->user_id !== $user->id && 
            !$trade->requests()->where('requester_id', $user->id)->where('status', 'accepted')->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'assigned_to' => 'required|exists:users,id'
        ]);

        $task = $trade->tasks()->create([
            'created_by' => $user->id,
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description
        ]);

        $task->load(['creator', 'assignee']);

        // Broadcast task update
        $this->broadcastTaskUpdate($trade->id, $task);

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    public function toggleTask(Request $request, TradeTask $task)
    {
        $user = Auth::user();
        
        // Check if user is assigned to this task
        if ($task->assigned_to !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update([
            'completed' => !$task->completed,
            'completed_at' => !$task->completed ? now() : null
        ]);

        $task->load(['creator', 'assignee']);

        // Broadcast task update
        $this->broadcastTaskUpdate($task->trade_id, $task);

        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }

    private function broadcastMessage($tradeId, $message)
    {
        try {
            // Check if Pusher is configured
            if (!config('broadcasting.connections.pusher.key') || 
                config('broadcasting.connections.pusher.key') === 'your-pusher-key') {
                return; // Skip broadcasting if Pusher is not configured
            }

            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            );

            $pusher->trigger("trade-{$tradeId}", 'new-message', [
                'message' => $message,
                'sender_name' => $message->sender->firstname . ' ' . $message->sender->lastname,
                'timestamp' => $message->created_at->format('g:i A')
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the message sending
            \Log::error('Pusher broadcasting error: ' . $e->getMessage());
        }
    }

    private function broadcastTaskUpdate($tradeId, $task)
    {
        try {
            // Check if Pusher is configured
            if (!config('broadcasting.connections.pusher.key') || 
                config('broadcasting.connections.pusher.key') === 'your-pusher-key') {
                return; // Skip broadcasting if Pusher is not configured
            }

            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            );

            $pusher->trigger("trade-{$tradeId}", 'task-updated', [
                'task' => $task,
                'creator_name' => $task->creator->firstname . ' ' . $task->creator->lastname,
                'assignee_name' => $task->assignee->firstname . ' ' . $task->assignee->lastname
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the task update
            \Log::error('Pusher broadcasting error: ' . $e->getMessage());
        }
    }
}
