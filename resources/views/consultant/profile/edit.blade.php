@extends('consultant.layouts.app')

@section('header', 'Edit Profile')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <form action="{{ route('consultant.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="specialization" class="block text-gray-700 text-sm font-bold mb-2">Specialization</label>
                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $profile->specialization) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('specialization') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Biography</label>
                <textarea name="bio" id="bio" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="hourly_rate" class="block text-gray-700 text-sm font-bold mb-2">Hourly Rate</label>
                <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('hourly_rate') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="slot_duration" class="block text-gray-700 text-sm font-bold mb-2">Slot Duration (Minutes)</label>
                <input type="number" name="slot_duration" id="slot_duration" value="{{ old('slot_duration', $profile->slot_duration) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('slot_duration') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="profile_photo" class="block text-gray-700 text-sm font-bold mb-2">Profile Photo</label>
                @if($profile->profile_photo_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $profile->profile_photo_path) }}" alt="Profile Photo" class="w-32 h-32 object-cover rounded-full">
                    </div>
                @endif
                <input type="file" name="profile_photo" id="profile_photo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('profile_photo') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
