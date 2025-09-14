@extends('layouts.admin')

@section('title', 'Overview')
@section('subtitle', 'Dashboard overview and key metrics')

@section('content')
<div class="space-y-6">
    <!-- Top Row - 3 Blue Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $metrics['totalUsers']['value'] }}</p>
                    <p class="text-sm {{ $metrics['totalUsers']['change'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $metrics['totalUsers']['changeText'] }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $metrics['activeUsers']['value'] }}</p>
                    <p class="text-sm {{ $metrics['activeUsers']['change'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $metrics['activeUsers']['changeText'] }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Skills Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Skills</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $metrics['totalSkills']['value'] }}</p>
                    <p class="text-sm {{ $metrics['totalSkills']['change'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $metrics['totalSkills']['changeText'] }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Section - 2 Blue Cards + Green Popular Skills -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Side - 2 Blue Cards Stacked -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Skill Exchanges Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Skill Exchanges</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $metrics['skillExchanges']['value'] }}</p>
                        <p class="text-sm {{ $metrics['skillExchanges']['change'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $metrics['skillExchanges']['changeText'] }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $metrics['monthlyRevenue']['value'] }}</p>
                        <p class="text-sm {{ $metrics['monthlyRevenue']['change'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $metrics['monthlyRevenue']['changeText'] }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Green Popular Skills -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 h-full">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Skills</h3>
                <div class="space-y-4">
                    @forelse($metrics['popularSkills'] as $skill)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
        <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $skill['name'] }}</span>
                                    <div class="text-xs text-gray-500">{{ $skill['category'] }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">{{ $skill['users_count'] }} users</span>
                                <span class="text-sm {{ $skill['growth'] > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $skill['growth'] > 0 ? '+' : '' }}{{ $skill['growth'] }}%
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No skills data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section - Red Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-4">
            @forelse($metrics['recentActivity'] as $activity)
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $activity['type'] }}</span>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No recent activity</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection