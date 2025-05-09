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
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.edit_company') }}</h1>
        
        <form action="{{ route('admin.companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                @if($company->logo)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-32 w-auto">
                    </div>
                @endif
                
                <label for="logo" class="block text-gray-700 mb-2">{{ __('messages.company_logo') }}</label>
                <input id="logo" type="file" class="w-full px-4 py-2 border rounded-md @error('logo') border-red-500 @enderror" name="logo" accept="image/*">
                
                @error('logo')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 mb-2">{{ __('messages.company_name') }}</label>
                <input id="name" type="text" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror" name="name" value="{{ old('name', $company->name) }}" required>
                
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 mb-2">{{ __('messages.company_description') }}</label>
                <textarea id="description" class="w-full px-4 py-2 border rounded-md @error('description') border-red-500 @enderror" name="description" rows="4">{{ old('description', $company->description) }}</textarea>
                
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="theme" class="block text-gray-700 mb-2">{{ __('messages.company_theme') }}</label>
                <select id="theme" name="theme" class="w-full px-4 py-2 border rounded-md @error('theme') border-red-500 @enderror" required>
                    <option value="orange" {{ old('theme', $company->theme) == 'orange' ? 'selected' : '' }}>{{ __('messages.theme_orange') }}</option>
                    <option value="blue" {{ old('theme', $company->theme) == 'blue' ? 'selected' : '' }}>{{ __('messages.theme_blue') }}</option>
                    <option value="green" {{ old('theme', $company->theme) == 'green' ? 'selected' : '' }}>{{ __('messages.theme_green') }}</option>
                    <option value="purple" {{ old('theme', $company->theme) == 'purple' ? 'selected' : '' }}>{{ __('messages.theme_purple') }}</option>
                    <option value="red" {{ old('theme', $company->theme) == 'red' ? 'selected' : '' }}>{{ __('messages.theme_red') }}</option>
                </select>
                
                @error('theme')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">{{ __('messages.company_admin') }}</h3>
                
                <div class="bg-gray-50 p-4 rounded-md">
                    <p class="font-medium">{{ $company->admin ? $company->admin->name : '-' }}</p>
                    <p class="text-gray-600">{{ $company->admin ? $company->admin->email : '-' }}</p>
                </div>
                
                <p class="text-sm text-gray-500 mt-2">
                    {{ __('messages.admin_edit_note') }}
                </p>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection