<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_verified', false)->get();
        return view('admin.dashboard', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        $user->is_verified = true;
        $user->save();
        return redirect()->route('admin.dashboard')->with('success', 'User approved!');
    }
    
    public function reject(User $user)
    {
        $user->is_verified = false;
        $user->save();
        return redirect()->route('admin.dashboard')->with('success', 'User rejected!');
    }

    public function show(User $user)
    {
        return view('admin.user_show', compact('user'));
    }

    public function skillsIndex()
    {
        $skills = \App\Models\Skill::all();
        return view('admin.skills.index', compact('skills'));
    }
}