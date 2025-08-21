<?php
public function index()
{
    $pendingUsers = \App\Models\User::where('status', 'pending')->get();
    return view('admin.dashboard', compact('pendingUsers'));
}