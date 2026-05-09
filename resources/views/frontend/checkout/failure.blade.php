@extends('frontend.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-12">
        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ __('frontend.payment_failed') }}</h1>
        <p class="text-lg text-gray-600 mb-8">
            {{ session('error') ?? __('frontend.payment_failed_desc') }}
        </p>
        <a href="{{ route('consultants.index') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition duration-300">
            {{ __('frontend.try_again') }}
        </a>
    </div>
</div>
@endsection
