@extends('layouts.auth')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.company_registration') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('messages.register_description') }}</p>
        
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <a href="{{ route('register.company') }}" class="flex-1 bg-primary text-white py-3 px-4 rounded-md hover:bg-primary-dark text-center">
                {{ __('messages.register_company') }}
            </a>
            <a href="{{ route('register') }}" class="flex-1 border border-primary text-primary py-3 px-4 rounded-md hover:bg-primary-light text-center">
                {{ __('messages.register_employee') }}
            </a>
        </div>
        
        <form method="POST" action="{{ route('register.company.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.personal_information') }}</h2>
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">{{ __('messages.name') }}</label>
                    <input id="name" type="text" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">{{ __('messages.email') }}</label>
                    <input id="email" type="email" class="w-full px-4 py-2 border rounded-md @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 mb-2">{{ __('messages.password') }}</label>
                    <input id="password" type="password" class="w-full px-4 py-2 border rounded-md @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                    
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="password-confirm" class="block text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
                    <input id="password-confirm" type="password" class="w-full px-4 py-2 border rounded-md" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.company_information') }}</h2>
                
                <div class="mb-4">
                    <label for="company_name" class="block text-gray-700 mb-2">{{ __('messages.company_name') }}</label>
                    <input id="company_name" type="text" class="w-full px-4 py-2 border rounded-md @error('company_name') border-red-500 @enderror" name="company_name" value="{{ old('company_name') }}" required>
                    
                    @error('company_name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="company_description" class="block text-gray-700 mb-2">{{ __('messages.company_description') }}</label>
                    <textarea id="company_description" class="w-full px-4 py-2 border rounded-md @error('company_description') border-red-500 @enderror" name="company_description" rows="3">{{ old('company_description') }}</textarea>
                    
                    @error('company_description')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="theme" class="block text-gray-700 mb-2">{{ __('messages.select_theme') }}</label>
                    <select id="theme" name="theme" class="w-full px-4 py-2 border rounded-md @error('theme') border-red-500 @enderror" required>
                        <option value="orange" {{ old('theme') == 'orange' ? 'selected' : '' }}>{{ __('messages.theme_orange') }}</option>
                        <option value="blue" {{ old('theme') == 'blue' ? 'selected' : '' }}>{{ __('messages.theme_blue') }}</option>
                        <option value="green" {{ old('theme') == 'green' ? 'selected' : '' }}>{{ __('messages.theme_green') }}</option>
                        <option value="purple" {{ old('theme') == 'purple' ? 'selected' : '' }}>{{ __('messages.theme_purple') }}</option>
                        <option value="red" {{ old('theme') == 'red' ? 'selected' : '' }}>{{ __('messages.theme_red') }}</option>
                    </select>
                    
                    @error('theme')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="logo" class="block text-gray-700 mb-2">{{ __('messages.company_logo') }}</label>
                    <input id="logo" type="file" class="w-full px-4 py-2 border rounded-md @error('logo') border-red-500 @enderror" name="logo" accept="image/*">
                    
                    @error('logo')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark">
                    {{ __('messages.register') }}
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-gray-600">
                    {{ __('messages.already_have_account') }}
                    <a href="{{ route('login') }}" class="text-primary hover:underline">{{ __('messages.login') }}</a>
                </p>
            </div>
        </form>
    </div>
@endsection