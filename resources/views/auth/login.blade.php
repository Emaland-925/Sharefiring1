@extends('layouts.auth')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.login_title') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('messages.login_description') }}</p>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">{{ __('messages.email') }}</label>
                <input id="email" type="email" class="w-full px-4 py-2 border rounded-md @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <div class="flex justify-between mb-2">
                    <label for="password" class="text-gray-700">{{ __('messages.password') }}</label>
                    
                    @if (Route::has('password.request'))
                        <a class="text-primary text-sm hover:underline" href="{{ route('password.request') }}">
                            {{ __('messages.forgot_password') }}
                        </a>
                    @endif
                </div>
                
                <input id="password" type="password" class="w-full px-4 py-2 border rounded-md @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                
                @error('password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <div class="flex items-center">
                    <input class="mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="text-gray-700" for="remember">
                        {{ __('messages.remember_me') }}
                    </label>
                </div>
            </div>
            
            <div class="mb-6">
                <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark">
                    {{ __('messages.login') }}
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-gray-600">
                    {{ __('messages.dont_have_account') }}
                    <a href="{{ route('register') }}" class="text-primary hover:underline">{{ __('messages.register') }}</a>
                </p>
            </div>
        </form>
    </div>
@endsection