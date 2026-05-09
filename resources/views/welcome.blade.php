@extends('frontend.layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="bg-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4">
                {{ __('frontend.hero_title') }}
            </h1>
            <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                {{ __('frontend.hero_subtitle') }}
            </p>
            <a href="{{ route('consultants.index') }}" class="inline-block bg-white text-blue-600 font-bold py-3 px-8 rounded-full hover:bg-gray-100 transition duration-300">
                {{ __('frontend.find_consultant') }}
            </a>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">{{ __('frontend.featured_categories') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('consultants.index', ['category' => $category->slug]) }}" class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition duration-300">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Featured Consultants -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">{{ __('frontend.featured_consultants') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredConsultants as $consultant)
                    <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                        <div class="p-6 flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $consultant->name }}</h3>
                            <p class="text-sm text-blue-600 mb-4">{{ $consultant->consultantProfile->title ?? '' }}</p>
                            <p class="text-gray-600 line-clamp-3 mb-4">{{ $consultant->consultantProfile->bio ?? '' }}</p>
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span class="font-medium mr-1">{{ __('frontend.experience') }}:</span> {{ $consultant->consultantProfile->experience_years ?? 0 }} {{ __('frontend.years') }}
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                            <span class="font-bold text-gray-900">${{ number_format($consultant->consultantProfile->hourly_rate ?? 0, 2) }}</span>
                            <a href="{{ route('consultants.show', $consultant) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                {{ __('frontend.view_profile') }} &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('consultants.index') }}" class="inline-block border-2 border-blue-600 text-blue-600 font-bold py-2 px-6 rounded-full hover:bg-blue-50 transition duration-300">
                    View All
                </a>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-12">{{ __('frontend.how_it_works') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="w-16 h-16 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl font-bold mb-4">1</div>
                <h3 class="text-xl font-bold mb-2">{{ __('frontend.step_1') }}</h3>
                <p class="text-gray-600">{{ __('frontend.step_1_desc') }}</p>
            </div>
            <div>
                <div class="w-16 h-16 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl font-bold mb-4">2</div>
                <h3 class="text-xl font-bold mb-2">{{ __('frontend.step_2') }}</h3>
                <p class="text-gray-600">{{ __('frontend.step_2_desc') }}</p>
            </div>
            <div>
                <div class="w-16 h-16 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl font-bold mb-4">3</div>
                <h3 class="text-xl font-bold mb-2">{{ __('frontend.step_3') }}</h3>
                <p class="text-gray-600">{{ __('frontend.step_3_desc') }}</p>
            </div>
        </div>
    </div>
@endsection
