@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.companies') }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.add_company') }}</h1>
        
        <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.company_information') }}</h2>
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">{{ __('messages.company_name') }}</label>
                    <input id="name" type="text" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required>
                    
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 mb-2">{{ __('messages.company_description') }}</label>
                    <textarea id="description" class="w-full px-4 py-2 border rounded-md @error('description') border-red-500 @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                    
                    @error('description')
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
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.admin_information') }}</h2>
                
                <div class="mb-4">
                    <label for="admin_name" class="block text-gray-700 mb-2">{{ __('messages.name') }}</label>
                    <input id="admin_name" type="text" class="w-full px-4 py-2 border rounded-md @error('admin_name') border-red-500 @enderror" name="admin_name" value="{{ old('admin_name') }}" required>
                    
                    @error('admin_name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="admin_email" class="block text-gray-700 mb-2">{{ __('messages.email') }}</label>
                    <input id="admin_email" type="email" class="w-full px-4 py-2 border rounded-md @error('admin_email') border-red-500 @enderror" name="admin_email" value="{{ old('admin_email') }}" required>
                    
                    @error('admin_email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="admin_password" class="block text-gray-700 mb-2">{{ __('messages.password') }}</label>
                    <input id="admin_password" type="password" class="w-full px-4 py-2 border rounded-md @error('admin_password') border-red-500 @enderror" name="admin_password" required>
                    
                    @error('admin_password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="admin_password_confirmation" class="block text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
                    <input id="admin_password_confirmation" type="password" class="w-full px-4 py-2 border rounded-md" name="admin_password_confirmation" required>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.create') }}
                </button>
            </div>
        </form>
    </div>
@endsection