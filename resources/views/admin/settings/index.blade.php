@extends('layouts.admin')

@section('title', 'Settings')
@section('subtitle', 'Manage application settings and configuration')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h3>
        
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                    <input id="site_name" name="site_name" type="text" value="{{ old('site_name', $settings['site_name']) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    @error('site_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                    <input id="site_description" name="site_description" type="text" value="{{ old('site_description', $settings['site_description']) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    @error('site_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="max_trades_per_user" class="block text-sm font-medium text-gray-700 mb-2">Max Trades Per User</label>
                    <input id="max_trades_per_user" name="max_trades_per_user" type="number" min="1" max="50" value="{{ old('max_trades_per_user', $settings['max_trades_per_user']) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    <p class="mt-1 text-sm text-gray-500">Maximum number of active trades a user can have</p>
                    @error('max_trades_per_user')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="trade_duration_days" class="block text-sm font-medium text-gray-700 mb-2">Default Trade Duration (Days)</label>
                    <input id="trade_duration_days" name="trade_duration_days" type="number" min="1" max="365" value="{{ old('trade_duration_days', $settings['trade_duration_days']) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    <p class="mt-1 text-sm text-gray-500">Default duration for new trades in days</p>
                    @error('trade_duration_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Feature Toggles</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="require_email_verification" class="text-sm font-medium text-gray-700">Require Email Verification</label>
                            <p class="text-sm text-gray-500">Users must verify their email before using the platform</p>
                        </div>
                        <div class="flex items-center">
                            <input id="require_email_verification" name="require_email_verification" type="checkbox" value="1" 
                                   {{ old('require_email_verification', $settings['require_email_verification']) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label for="allow_anonymous_trades" class="text-sm font-medium text-gray-700">Allow Anonymous Trades</label>
                            <p class="text-sm text-gray-500">Allow users to create trades without revealing their identity</p>
                        </div>
                        <div class="flex items-center">
                            <input id="allow_anonymous_trades" name="allow_anonymous_trades" type="checkbox" value="1" 
                                   {{ old('allow_anonymous_trades', $settings['allow_anonymous_trades']) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <label for="maintenance_mode" class="text-sm font-medium text-gray-700">Maintenance Mode</label>
                            <p class="text-sm text-gray-500">Put the site in maintenance mode (admin access only)</p>
                        </div>
                        <div class="flex items-center">
                            <input id="maintenance_mode" name="maintenance_mode" type="checkbox" value="1" 
                                   {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">System Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Application Details</h4>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Laravel Version:</dt>
                        <dd class="text-sm text-gray-900">{{ app()->version() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">PHP Version:</dt>
                        <dd class="text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Environment:</dt>
                        <dd class="text-sm text-gray-900">{{ app()->environment() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Debug Mode:</dt>
                        <dd class="text-sm text-gray-900">{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Database Information</h4>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Total Users:</dt>
                        <dd class="text-sm text-gray-900">{{ \App\Models\User::count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Total Skills:</dt>
                        <dd class="text-sm text-gray-900">{{ \App\Models\Skill::count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Total Trades:</dt>
                        <dd class="text-sm text-gray-900">{{ \App\Models\Trade::count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Active Trades:</dt>
                        <dd class="text-sm text-gray-900">{{ \App\Models\Trade::where('status', 'open')->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Cache Management -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Cache Management</h3>
        
        <div class="flex flex-wrap gap-4">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="inline">
                @csrf
                <input type="hidden" name="action" value="clear_cache">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Clear Cache
                </button>
            </form>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="inline">
                @csrf
                <input type="hidden" name="action" value="clear_views">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Clear Views
                </button>
            </form>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="inline">
                @csrf
                <input type="hidden" name="action" value="clear_routes">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Clear Routes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
