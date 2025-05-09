@extends('layouts.company')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.company_settings') }}</h1>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-6">{{ __('messages.company_profile') }}</h2>
        
        <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data">
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
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection