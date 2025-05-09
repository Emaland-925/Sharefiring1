@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('courses.index') }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.create_course') }}</h1>
        
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 mb-2">{{ __('messages.course_title') }}</label>
                <input id="title" type="text" class="w-full px-4 py-2 border rounded-md @error('title') border-red-500 @enderror" name="title" value="{{ old('title') }}" required>
                
                @error('title')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 mb-2">{{ __('messages.course_description') }}</label>
                <textarea id="description" class="w-full px-4 py-2 border rounded-md @error('description') border-red-500 @enderror" name="description" rows="4">{{ old('description') }}</textarea>
                
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 mb-2">{{ __('messages.course_category') }}</label>
                <select id="category_id" name="category_id" class="w-full px-4 py-2 border rounded-md @error('category_id') border-red-500 @enderror">
                    <option value="">{{ __('messages.select_option') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                
                @error('category_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="points_reward" class="block text-gray-700 mb-2">{{ __('messages.points_reward') }}</label>
                <input id="points_reward" type="number" class="w-full px-4 py-2 border rounded-md @error('points_reward') border-red-500 @enderror" name="points_reward" value="{{ old('points_reward', 10) }}" min="0">
                
                @error('points_reward')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.create') }}
                </button>
            </div>
        </form>
    </div>
@endsection