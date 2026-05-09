@extends('consultant.layouts.app')

@section('header', __('consultant.dashboard'))

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __('consultant.welcome_dashboard') }}
        </div>
    </div>
@endsection
