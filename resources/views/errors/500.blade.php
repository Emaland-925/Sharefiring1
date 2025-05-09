@extends('layouts.app')

@section('content')
    <div class="flex flex-col items-center justify-center py-16">
        <div class="text-6xl font-bold text-primary mb-4">500</div>
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.server_error') }}</h1>
        <p class="text-gray-600 mb-8">{{ __('messages.server_error_description') }}</p>
        <a href="{{ route('home') }}" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
            {{ __('messages.back_to_home') }}
        </a>
    </div>
@endsection