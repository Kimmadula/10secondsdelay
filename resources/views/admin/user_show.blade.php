
@extends('layouts.admin')

@section('title', 'User Details')
@section('subtitle', 'View user information and details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">User Details</h3>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Back to Users
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Information -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->username }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->role ?? 'user' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Verification Status</label>
                    <div class="mt-1">
                        @if($user->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending Approval
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Skill</label>
                    <p class="mt-1 text-sm text-gray-900">{{ optional($user->skill)->name ?? 'No skill selected' }}</p>
                </div>
                
                @if($user->address)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->address }}</p>
                </div>
                @endif
                
                @if($user->bdate)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Birth Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $user->bdate->format('M d, Y') }}</p>
                </div>
                @endif
                
                @if($user->gender)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($user->gender) }}</p>
                </div>
                @endif
            </div>
            
            <!-- User Photo -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                    <div class="mt-2">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" 
                                 class="w-48 h-48 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="w-48 h-48 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 text-sm">No photo uploaded</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                @if(!$user->is_verified)
                <div class="space-y-2">
                    <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Approve User
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.reject', $user->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Reject User
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection