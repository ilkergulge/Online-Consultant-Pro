@extends('consultant.layouts.app')

@section('header', __('consultant.availability'))

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <h3 class="text-lg font-bold mb-4">{{ __('consultant.add_availability') }}</h3>
            <form action="{{ route('consultant.availability.store') }}" method="POST" class="flex items-end gap-4">
                @csrf
                <div>
                    <label for="day_of_week" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.day_of_week') }}</label>
                    <select name="day_of_week" id="day_of_week" class="shadow border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="1">{{ __('consultant.monday') }}</option>
                        <option value="2">{{ __('consultant.tuesday') }}</option>
                        <option value="3">{{ __('consultant.wednesday') }}</option>
                        <option value="4">{{ __('consultant.thursday') }}</option>
                        <option value="5">{{ __('consultant.friday') }}</option>
                        <option value="6">{{ __('consultant.saturday') }}</option>
                        <option value="0">{{ __('consultant.sunday') }}</option>
                    </select>
                </div>
                <div>
                    <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.start_time') }}</label>
                    <input type="time" name="start_time" id="start_time" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">{{ __('consultant.end_time') }}</label>
                    <input type="time" name="end_time" id="end_time" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('consultant.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('consultant.day_of_week') }}</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('consultant.start_time') }}</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('consultant.end_time') }}</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('consultant.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($availabilities as $availability)
                        @php
                            $days = [
                                0 => __('consultant.sunday'),
                                1 => __('consultant.monday'),
                                2 => __('consultant.tuesday'),
                                3 => __('consultant.wednesday'),
                                4 => __('consultant.thursday'),
                                5 => __('consultant.friday'),
                                6 => __('consultant.saturday'),
                            ];
                        @endphp
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $days[$availability->day_of_week] }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <form action="{{ route('consultant.availability.destroy', $availability) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('consultant.are_you_sure') }}')">{{ __('consultant.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
