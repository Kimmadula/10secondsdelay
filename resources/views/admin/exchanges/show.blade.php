@extends('layouts.admin')

@section('title', 'Exchange Details')
@section('subtitle', 'View detailed information about this skill exchange')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.exchanges.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Exchanges
        </a>
    </div>

    <!-- Exchange Overview -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Exchange Overview</h3>
            @php
                $statusColors = [
                    'open' => 'bg-green-100 text-green-800',
                    'ongoing' => 'bg-yellow-100 text-yellow-800',
                    'closed' => 'bg-gray-100 text-gray-800'
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$trade->status] }}">
                {{ ucfirst($trade->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Information -->
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">User Information</h4>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">
                                {{ substr($trade->user->firstname, 0, 1) }}{{ substr($trade->user->lastname, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $trade->user->firstname }} {{ $trade->user->lastname }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $trade->user->email }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <strong>Username:</strong> {{ $trade->user->username }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <strong>Location:</strong> {{ $trade->user->address }}
                    </div>
                </div>
            </div>

            <!-- Exchange Details -->
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Exchange Details</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Offering:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $trade->offeringSkill->name }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Looking For:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $trade->lookingSkill->name }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Start Date:</span>
                        <span class="text-sm text-gray-900">{{ $trade->start_date->format('M d, Y') }}</span>
                    </div>
                    @if($trade->end_date)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">End Date:</span>
                        <span class="text-sm text-gray-900">{{ $trade->end_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Availability & Preferences -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability & Preferences</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Time Availability</h4>
                @if($trade->available_from && $trade->available_to)
                    <p class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($trade->available_from)->format('h:i A') }} - 
                        {{ \Carbon\Carbon::parse($trade->available_to)->format('h:i A') }}
                    </p>
                @else
                    <p class="text-sm text-gray-500">Not specified</p>
                @endif
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Preferred Days</h4>
                @if($trade->preferred_days && count($trade->preferred_days) > 0)
                    <div class="flex flex-wrap gap-1">
                        @foreach($trade->preferred_days as $day)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $day }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Not specified</p>
                @endif
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-2">Preferences</h4>
                <div class="space-y-1">
                    <p class="text-sm text-gray-600">
                        <strong>Gender:</strong> {{ ucfirst($trade->gender_pref) }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Session Type:</strong> {{ ucfirst($trade->session_type) }}
                    </p>
                    @if($trade->location)
                    <p class="text-sm text-gray-600">
                        <strong>Location:</strong> {{ $trade->location }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Trade Requests -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Requests ({{ $trade->requests->count() }})</h3>
        
        @if($trade->requests->count() > 0)
            <div class="space-y-4">
                @foreach($trade->requests as $request)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ substr($request->requester->firstname, 0, 1) }}{{ substr($request->requester->lastname, 0, 1) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $request->requester->firstname }} {{ $request->requester->lastname }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $request->requester->email }}</div>
                                </div>
                            </div>
                            @php
                                $requestStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'accepted' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $requestStatusColors[$request->status] }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        @if($request->message)
                            <p class="text-sm text-gray-600 mt-2">{{ $request->message }}</p>
                        @endif
                        <div class="text-xs text-gray-500 mt-2">
                            Requested on {{ $request->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No trade requests yet.</p>
        @endif
    </div>

    <!-- Messages -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages ({{ $trade->messages->count() }})</h3>
        
        @if($trade->messages->count() > 0)
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @foreach($trade->messages as $message)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-700">
                                        {{ substr($message->sender->firstname, 0, 1) }}{{ substr($message->sender->lastname, 0, 1) }}
                                    </span>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-900">
                                    {{ $message->sender->firstname }} {{ $message->sender->lastname }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">{{ $message->created_at->format('M d, h:i A') }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $message->message }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No messages yet.</p>
        @endif
    </div>

    <!-- Tasks -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tasks ({{ $trade->tasks->count() }})</h3>
        
        @if($trade->tasks->count() > 0)
            <div class="space-y-3">
                @foreach($trade->tasks as $task)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                    @if($task->completed)
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @else
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ $task->description }}</p>
                                <div class="text-xs text-gray-500">
                                    Created by {{ $task->creator->firstname }} {{ $task->creator->lastname }}
                                    @if($task->assignee)
                                        • Assigned to {{ $task->assignee->firstname }} {{ $task->assignee->lastname }}
                                    @endif
                                    • {{ $task->created_at->format('M d, Y h:i A') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No tasks yet.</p>
        @endif
    </div>
</div>
@endsection
