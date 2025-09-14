@extends('layouts.admin')

@section('title', 'Reports')
@section('subtitle', 'Analytics and insights for your platform')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['new_users_30d'] }} this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Trades</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_trades'] }}</p>
                    <p class="text-xs text-green-600">+{{ $stats['new_trades_30d'] }} this month</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Trades</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_trades'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['ongoing_trades'] }} ongoing</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Messages</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_messages'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['total_requests'] }} requests</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Registration Trends -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registration Trends (Last 7 Days)</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($userTrends as $date => $count)
                    @php
                        $maxCount = max($userTrends);
                        $height = $count > 0 ? ($count / $maxCount * 200) : 4;
                    @endphp
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-blue-200 rounded-t" style="height: {{ $height }}px"></div>
                        <div class="text-xs text-gray-600 mt-2">{{ \Carbon\Carbon::parse($date)->format('M d') }}</div>
                        <div class="text-xs font-medium text-gray-900">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Trade Creation Trends -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Trade Creation Trends (Last 7 Days)</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($tradeTrends as $date => $count)
                    @php
                        $maxCount = max($tradeTrends);
                        $height = $count > 0 ? ($count / $maxCount * 200) : 4;
                    @endphp
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-green-200 rounded-t" style="height: {{ $height }}px"></div>
                        <div class="text-xs text-gray-600 mt-2">{{ \Carbon\Carbon::parse($date)->format('M d') }}</div>
                        <div class="text-xs font-medium text-gray-900">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Skills -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Top Skills by Usage</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.export', ['type' => 'skills', 'format' => 'csv']) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skill</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offering</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Looking For</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Usage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topSkills as $skill)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $skill->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $skill->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $skill->users_count ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $skill->trades_offering_count ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $skill->trades_looking_count ?? 0 }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ ($skill->users_count ?? 0) + ($skill->trades_offering_count ?? 0) + ($skill->trades_looking_count ?? 0) }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-sm text-gray-500">No skills data available.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Reports</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">User Reports</h4>
                <p class="text-sm text-gray-600 mb-4">Export user data and registration trends</p>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        CSV
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'pdf']) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        PDF
                    </a>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Trade Reports</h4>
                <p class="text-sm text-gray-600 mb-4">Export trade data and completion rates</p>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.export', ['type' => 'trades', 'format' => 'csv']) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        CSV
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'trades', 'format' => 'pdf']) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        PDF
                    </a>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Activity Reports</h4>
                <p class="text-sm text-gray-600 mb-4">Export message and activity data</p>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.export', ['type' => 'activity', 'format' => 'csv']) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        CSV
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'activity', 'format' => 'pdf']) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
