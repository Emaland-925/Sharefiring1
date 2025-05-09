@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('courses.edit', $course->id) }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.edit_lesson') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('messages.course') }}: {{ $course->title }}</p>
        
        <form action="{{ route('lessons.update', [$course->id, $lesson->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 mb-2">{{ __('messages.lesson_title') }}</label>
                <input id="title" type="text" class="w-full px-4 py-2 border rounded-md @error('title') border-red-500 @enderror" name="title" value="{{ old('title', $lesson->title) }}" required>
                
                @error('title')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-gray-700 mb-2">{{ __('messages.lesson_content') }}</label>
                <textarea id="content" class="w-full px-4 py-2 border rounded-md @error('content') border-red-500 @enderror" name="content" rows="8">{{ old('content', $lesson->content) }}</textarea>
                
                @error('content')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="video" class="block text-gray-700 mb-2">{{ __('messages.lesson_video') }}</label>
                
                @if($lesson->video_url)
                    <div class="mb-2">
                        <p class="text-sm text-gray-600">{{ __('messages.current_video') }}: {{ basename($lesson->video_url) }}</p>
                    </div>
                @endif
                
                <input id="video" type="file" class="w-full px-4 py-2 border rounded-md @error('video') border-red-500 @enderror" name="video" accept="video/*">
                
                @error('video')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="order_num" class="block text-gray-700 mb-2">{{ __('messages.lesson_order') }}</label>
                <input id="order_num" type="number" class="w-full px-4 py-2 border rounded-md @error('order_num') border-red-500 @enderror" name="order_num" value="{{ old('order_num', $lesson->order_num) }}" min="1" required>
                
                @error('order_num')
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