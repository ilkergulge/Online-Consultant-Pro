@extends('admin.layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">Total Users</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $metrics['total_users'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">Consultants</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $metrics['total_consultants'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">Appointments</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $metrics['total_appointments'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
        <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wider mb-2">Total Revenue</h3>
        <p class="text-3xl font-bold text-gray-800">{{ number_format($metrics['total_revenue'], 2) }} ₺</p>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Appointments</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Consultant</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($metrics['recent_appointments'] as $appointment)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $appointment->client->name ?? 'N/A' }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $appointment->consultant->name ?? 'N/A' }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $appointment->scheduled_at }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                    <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                    <span class="relative">{{ ucfirst($appointment->status) }}</span>
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No recent appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
