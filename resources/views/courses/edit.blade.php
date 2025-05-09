@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('courses.show', $course->id) }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.edit_course') }}</h1>
        
        <form action="{{ route('courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 mb-2">{{ __('messages.course_title') }}</label>
                <input id="title" type="text" class="w-full px-4 py-2 border rounded-md @error('title') border-red-500 @enderror" name="title" value="{{ old('title', $course->title) }}" required>
                
                @error('title')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 mb-2">{{ __('messages.course_description') }}</label>
                <textarea id="description" class="w-full px-4 py-2 border rounded-md @error('description') border-red-500 @enderror" name="description" rows="4">{{ old('description', $course->description) }}</textarea>
                
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 mb-2">{{ __('messages.course_category') }}</label>
                <select id="category_id" name="category_id" class="w-full px-4 py-2 border rounded-md @error('category_id') border-red-500 @enderror">
                    <option value="">{{ __('messages.select_option') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
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
                <input id="points_reward" type="number" class="w-full px-4 py-2 border rounded-md @error('points_reward') border-red-500 @enderror" name="points_reward" value="{{ old('points_reward', $course->points_reward) }}" min="0">
                
                @error('points_reward')
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
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">{{ __('messages.course_content') }}</h2>
                    <a href="{{ route('lessons.create', $course->id) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                        {{ __('messages.add_lesson') }}
                    </a>
                </div>
                
                @if($course->lessons->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->lessons->sortBy('order_num') as $lesson)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold mb-1">{{ $lesson->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ __('messages.lesson_order') }}: {{ $lesson->order_num }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('lessons.edit', [$course->id, $lesson->id]) }}" class="text-primary hover:underline">{{ __('messages.edit') }}</a>
                                        <form action="{{ route('lessons.destroy', [$course->id, $lesson->id]) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">{{ __('messages.delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">{{ __('messages.no_lessons') }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">{{ __('messages.course_quizzes') }}</h2>
                    <a href="{{ route('quizzes.create', $course->id) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                        {{ __('messages.add_quiz') }}
                    </a>
                </div>
                
                @if($course->quizzes->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->quizzes as $quiz)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold mb-1">{{ $quiz->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ __('messages.questions') }}: {{ $quiz->questions->count() }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('quizzes.edit', $quiz->id) }}" class="text-primary hover:underline">{{ __('messages.edit') }}</a>
                                        <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">{{ __('messages.delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">{{ __('messages.no_quizzes') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection