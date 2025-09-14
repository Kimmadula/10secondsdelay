@extends('layouts.admin')

@section('title', 'Message Details')
@section('subtitle', 'View conversation and reply to messages')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.messages.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Messages
        </a>
    </div>

    <!-- Trade Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Trade Owner</h4>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                            {{ substr($message->trade->user->firstname, 0, 1) }}{{ substr($message->trade->user->lastname, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $message->trade->user->firstname }} {{ $message->trade->user->lastname }}
                        </div>
                        <div class="text-sm text-gray-500">{{ $message->trade->user->email }}</div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Skills Exchange</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Offering:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $message->trade->offeringSkill->name }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Looking For:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $message->trade->lookingSkill->name }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        @php
                            $statusColors = [
                                'open' => 'bg-green-100 text-green-800',
                                'ongoing' => 'bg-yellow-100 text-yellow-800',
                                'closed' => 'bg-gray-100 text-gray-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$message->trade->status] }}">
                            {{ ucfirst($message->trade->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Conversation</h3>
        
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @foreach($conversation as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900' }}">
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 {{ $msg->sender_id === auth()->id() ? 'bg-blue-400' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium {{ $msg->sender_id === auth()->id() ? 'text-white' : 'text-gray-700' }}">
                                    {{ substr($msg->sender->firstname, 0, 1) }}{{ substr($msg->sender->lastname, 0, 1) }}
                                </span>
                            </div>
                            <span class="ml-2 text-xs {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-600' }}">
                                {{ $msg->sender->firstname }} {{ $msg->sender->lastname }}
                            </span>
                        </div>
                        <p class="text-sm">{{ $msg->message }}</p>
                        <p class="text-xs {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                            {{ $msg->created_at->format('M d, h:i A') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Reply Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Send Reply</h3>
        
        <form method="POST" action="{{ route('admin.messages.reply', $message) }}" class="space-y-4">
            @csrf
            
            <div>
                <label for="reply" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                <textarea id="reply" name="reply" rows="4" required 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Type your reply here..."></textarea>
                @error('reply')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Send Reply
                </button>
            </div>
        </form>
    </div>

    <!-- Trade Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Actions</h3>
        
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.exchanges.show', $message->trade) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Trade Details
            </a>

            <a href="{{ route('admin.user.show', $message->trade->user) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                View User Profile
            </a>
        </div>
    </div>
</div>
@endsection
