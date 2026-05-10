@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h2 class="mt-6 text-center text-9xl font-extrabold text-blue-600">404</h2>
            <h3 class="mt-2 text-center text-3xl font-bold text-gray-900">{{ __('frontend.error_404_title') }}</h3>
            <p class="mt-2 text-center text-lg text-gray-600">{{ __('frontend.error_404_desc') }}</p>
        </div>
        <div class="mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                {{ __('frontend.back_to_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
