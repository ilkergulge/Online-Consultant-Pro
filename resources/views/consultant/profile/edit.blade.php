@extends('consultant.layouts.app')

@section('header', __('consultant.edit_profile'))

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('consultant.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.title') }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $profile->title ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.bio') }}</label>
                    <textarea name="bio" id="bio" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('bio', $profile->bio ?? '') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="hourly_rate" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.hourly_rate') }}</label>
                    <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate ?? 0) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="experience_years" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.experience_years') }}</label>
                    <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years', $profile->experience_years ?? 0) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.category') }}</label>
                    <select name="category_id" id="category_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">-- {{ __('consultant.category') }} --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($profile->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="slot_duration" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.slot_duration') }}</label>
                    <select name="slot_duration" id="slot_duration" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @foreach([15, 30, 45, 60, 90, 120] as $duration)
                            <option value="{{ $duration }}" {{ ($profile->slot_duration ?? 60) == $duration ? 'selected' : '' }}>{{ $duration }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('consultant.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
