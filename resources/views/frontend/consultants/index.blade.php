@extends('frontend.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">{{ __('frontend.filter_by') }}</h3>
                <form action="{{ route('consultants.index') }}" method="GET">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('frontend.search_name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.category') }}</label>
                        <select name="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">{{ __('frontend.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('frontend.hourly_rate') }}</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('frontend.min_price') }}" class="w-1/2 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('frontend.max_price') }}" class="w-1/2 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        {{ __('frontend.search') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Consultant List -->
        <div class="w-full md:w-3/4">
            @if($consultants->isEmpty())
                <div class="bg-white p-8 text-center rounded-lg shadow-sm text-gray-500">
                    {{ __('frontend.no_consultants_found') }}
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($consultants as $consultant)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 flex flex-col">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ $consultant->name }}</h3>
                                <p class="text-sm text-blue-600 mb-3">{{ $consultant->consultantProfile->title ?? '' }}</p>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $consultant->consultantProfile->bio ?? '' }}</p>
                                <div class="text-sm text-gray-500 mb-2">
                                    <span class="font-semibold">{{ __('frontend.experience') }}:</span> {{ $consultant->consultantProfile->experience_years ?? 0 }} {{ __('frontend.years') }}
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="text-lg font-bold text-gray-900">
                                    ${{ number_format($consultant->consultantProfile->hourly_rate ?? 0, 2) }}
                                </div>
                                <a href="{{ route('consultants.show', $consultant) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded font-medium transition duration-300">
                                    {{ __('frontend.view_profile') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $consultants->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
