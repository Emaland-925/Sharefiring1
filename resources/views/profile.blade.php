@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.profile') }}</h1>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                @if($user->profile_image)
                    <div class="mb-6 flex justify-center">
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover">
                    </div>
                @else
                    <div class="mb-6 flex justify-center">
                        <div class="h-32 w-32 rounded-full bg-primary-light flex items-center justify-center text-primary text-4xl font-medium">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                @endif
                
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-primary-light p-4 rounded-lg text-center">
                        <p class="text-gray-600 text-sm">{{ __('messages.points') }}</p>
                        <p class="text-2xl font-bold text-primary">{{ $user->points }}</p>
                    </div>
                    
                    <div class="bg-primary-light p-4 rounded-lg text-center">
                        <p class="text-gray-600 text-sm">{{ __('messages.level') }}</p>
                        <p class="text-2xl font-bold text-primary">{{ $user->level }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.account_info') }}</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('messages.role') }}</p>
                        <p class="font-medium">
                            @if($user->role === 'admin')
                                {{ __('messages.role_admin') }}
                            @elseif($user->role === 'company')
                                {{ __('messages.role_company') }}
                            @else
                                {{ __('messages.role_employee') }}
                            @endif
                        </p>
                    </div>
                    
                    @if($user->company)
                        <div>
                            <p class="text-sm text-gray-500">{{ __('messages.company') }}</p>
                            <p class="font-medium">{{ $user->company->name }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <p class="text-sm text-gray-500">{{ __('messages.language') }}</p>
                        <p class="font-medium">
                            {{ $user->language_preference === 'en' ? __('messages.english') : __('messages.arabic') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.profile_settings') }}</h2>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-2">{{ __('messages.name') }}</label>
                        <input id="name" type="text" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                        
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-2">{{ __('messages.email') }}</label>
                        <input id="email" type="email" class="w-full px-4 py-2 border rounded-md bg-gray-100" value="{{ $user->email }}" disabled>
                        <p class="text-sm text-gray-500 mt-1">{{ __('messages.email_readonly') }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="language_preference" class="block text-gray-700 mb-2">{{ __('messages.language') }}</label>
                        <select id="language_preference" name="language_preference" class="w-full px-4 py-2 border rounded-md @error('language_preference') border-red-500 @enderror">
                            <option value="en" {{ $user->language_preference === 'en' ? 'selected' : '' }}>{{ __('messages.english') }}</option>
                            <option value="ar" {{ $user->language_preference === 'ar' ? 'selected' : '' }}>{{ __('messages.arabic') }}</option>
                        </select>
                        
                        @error('language_preference')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="profile_image" class="block text-gray-700 mb-2">{{ __('messages.profile_picture') }}</label>
                        <input id="profile_image" type="file" class="w-full px-4 py-2 border rounded-md @error('profile_image') border-red-500 @enderror" name="profile_image" accept="image/*">
                        
                        @error('profile_image')
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
            
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.change_password') }}</h2>
                
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 mb-2">{{ __('messages.current_password') }}</label>
                        <input id="current_password" type="password" class="w-full px-4 py-2 border rounded-md @error('current_password') border-red-500 @enderror" name="current_password" required>
                        
                        @error('current_password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-2">{{ __('messages.new_password') }}</label>
                        <input id="password" type="password" class="w-full px-4 py-2 border rounded-md @error('password') border-red-500 @enderror" name="password" required>
                        
                        @error('password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 mb-2">{{ __('messages.confirm_new_password') }}</label>
                        <input id="password_confirmation" type="password" class="w-full px-4 py-2 border rounded-md" name="password_confirmation" required>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection