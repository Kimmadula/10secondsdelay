<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('trades.create') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="font-semibold text-lg">Post a Trade</div>
                    <div class="text-sm text-gray-600">Create a new skill trade post.</div>
                </a>
                <a href="{{ route('trades.matches') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="font-semibold text-lg">Matching Trades</div>
                    <div class="text-sm text-gray-600">Find trades that match your preferences.</div>
                </a>
                <a href="{{ route('trades.requests') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="font-semibold text-lg">Trade Requests</div>
                    <div class="text-sm text-gray-600">View incoming and outgoing requests.</div>
                </a>
                <a href="{{ route('trades.ongoing') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="font-semibold text-lg">Ongoing Trades</div>
                    <div class="text-sm text-gray-600">See active or accepted trades.</div>
                </a>
                <a href="{{ route('trades.notifications') }}" class="block bg-white p-6 shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="font-semibold text-lg">Notifications</div>
                    <div class="text-sm text-gray-600">Updates on requests and acceptances.</div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
