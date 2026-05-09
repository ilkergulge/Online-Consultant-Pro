@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="md:flex">
            <!-- Profile Info -->
            <div class="md:w-2/3 p-8 border-r border-gray-100">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $consultant->name }}</h1>
                    <p class="text-xl text-blue-600 mt-1">{{ $consultant->consultantProfile->title ?? '' }}</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-3">{{ __('frontend.about_me') }}</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $consultant->consultantProfile->bio ?? '' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8 bg-gray-50 p-4 rounded-lg">
                    <div>
                        <span class="block text-sm text-gray-500">{{ __('frontend.experience') }}</span>
                        <span class="font-semibold text-gray-900">{{ $consultant->consultantProfile->experience_years ?? 0 }} {{ __('frontend.years') }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">{{ __('frontend.category') }}</span>
                        <span class="font-semibold text-gray-900">{{ $consultant->consultantProfile->category->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="md:w-1/3 p-8 bg-gray-50">
                <div class="text-center mb-8">
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($consultant->consultantProfile->hourly_rate ?? 0, 2) }}</span>
                    <span class="text-gray-500 block">{{ __('frontend.per_session') }} ({{ $consultant->consultantProfile->slot_duration ?? 60 }} min)</span>
                </div>

                <h3 class="text-lg font-bold mb-4">{{ __('frontend.book_a_session') }}</h3>

                <form action="{{ route('consultants.show', $consultant) }}" method="GET" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('frontend.select_date') }}</label>
                    <input type="date" name="date" value="{{ $date }}" min="{{ now()->toDateString() }}" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </form>

                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">{{ __('frontend.available_slots') }}</h4>
                    @if(empty($availableSlots))
                        <p class="text-sm text-red-500">{{ __('frontend.no_slots_available') }}</p>
                    @else
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($availableSlots as $slot)
                                <form action="{{ route('checkout.process', $consultant) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $date }}">
                                    <input type="hidden" name="time" value="{{ $slot }}">
                                    <button type="submit" class="w-full bg-white border border-blue-200 text-blue-700 hover:bg-blue-600 hover:text-white py-2 rounded text-sm font-medium transition duration-200">
                                        {{ $slot }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
