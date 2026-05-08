@extends('admin.layouts.app')

@section('header', __('admin.settings'))

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <h3 class="text-lg font-bold mb-2">{{ __('admin.zoom_integration') }}</h3>
                    <label for="zoom_api_key" class="block text-gray-700 text-sm font-bold mb-2">{{ __('admin.zoom_api_key') }}</label>
                    <input type="text" name="settings[zoom_api_key]" id="zoom_api_key" value="{{ old('settings.zoom_api_key', $settings['zoom_api_key'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="zoom_api_secret" class="block text-gray-700 text-sm font-bold mb-2">{{ __('admin.zoom_api_secret') }}</label>
                    <input type="text" name="settings[zoom_api_secret]" id="zoom_api_secret" value="{{ old('settings.zoom_api_secret', $settings['zoom_api_secret'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4 mt-8">
                    <h3 class="text-lg font-bold mb-2">{{ __('admin.payment_gateways') }}</h3>
                    <label for="active_payment_gateway" class="block text-gray-700 text-sm font-bold mb-2">{{ __('admin.active_gateway') }}</label>
                    <select name="settings[active_payment_gateway]" id="active_payment_gateway" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="stripe" {{ ($settings['active_payment_gateway'] ?? '') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="iyzico" {{ ($settings['active_payment_gateway'] ?? '') == 'iyzico' ? 'selected' : '' }}>Iyzico</option>
                        <option value="shopier" {{ ($settings['active_payment_gateway'] ?? '') == 'shopier' ? 'selected' : '' }}>Shopier</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="stripe_public_key" class="block text-gray-700 text-sm font-bold mb-2">{{ __('admin.stripe_public_key') }}</label>
                    <input type="text" name="settings[stripe_public_key]" id="stripe_public_key" value="{{ old('settings.stripe_public_key', $settings['stripe_public_key'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4 mt-8">
                    <h3 class="text-lg font-bold mb-2">{{ __('admin.sms_configuration') }}</h3>
                    <label for="sms_api_key" class="block text-gray-700 text-sm font-bold mb-2">{{ __('admin.sms_api_key') }}</label>
                    <input type="text" name="settings[sms_api_key]" id="sms_api_key" value="{{ old('settings.sms_api_key', $settings['sms_api_key'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex items-center justify-between mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ __('admin.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
