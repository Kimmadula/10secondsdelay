<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $pendingUsers = \App\Models\User::where('status', 'pending')->get();
        return view('admin.dashboard', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        $user->status = 'approved';
        $user->save();
        return redirect()->route('admin.dashboard')->with('success', 'User approved!');
    }
    
    public function reject(User $user)
    {
        $user->status = 'rejected';
        $user->save();
        return redirect()->route('admin.dashboard')->with('success', 'User rejected!');
    }

    public function show(User $user)
    {
        return view('admin.user_show', compact('user'));
    }
}