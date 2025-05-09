@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('courses.edit', $quiz->course->id) }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.edit_quiz') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('messages.course') }}: {{ $quiz->course->title }}</p>
        
        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="title" class="block text-gray-700 mb-2">{{ __('messages.quiz_title') }}</label>
                <input id="title" type="text" class="w-full px-4 py-2 border rounded-md @error('title') border-red-500 @enderror" name="title" value="{{ old('title', $quiz->title) }}" required>
                
                @error('title')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-gray-700 mb-2">{{ __('messages.quiz_description') }}</label>
                <textarea id="description" class="w-full px-4 py-2 border rounded-md @error('description') border-red-500 @enderror" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
                
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="passing_score" class="block text-gray-700 mb-2">{{ __('messages.quiz_passing_score') }}</label>
                <input id="passing_score" type="number" class="w-full px-4 py-2 border rounded-md @error('passing_score') border-red-500 @enderror" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" required>
                
                @error('passing_score')
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
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">{{ __('messages.questions') }}</h2>
            <a href="{{ route('questions.create', $quiz->id) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                {{ __('messages.add_question') }}
            </a>
        </div>
        
        @if($quiz->questions->count() > 0)
            <div class="space-y-4">
                @foreach($quiz->questions as $question)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-grow">
                                <h3 class="font-semibold mb-2">{{ $question->question_text }}</h3>
                                <p class="text-sm text-gray-500 mb-2">
                                    {{ __('messages.question_type') }}: 
                                    @if($question->question_type === 'multiple_choice')
                                        {{ __('messages.multiple_choice') }}
                                    @elseif($question->question_type === 'true_false')
                                        {{ __('messages.true_false') }}
                                    @else
                                        {{ __('messages.text_answer') }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ __('messages.question_points') }}: {{ $question->points }}
                                </p>
                                
                                @if($question->question_type !== 'text' && $question->answers->count() > 0)
                                    <div class="mt-3 pl-4 border-l-2 border-gray-200">
                                        <p class="text-sm font-medium mb-1">{{ __('messages.answers') }}:</p>
                                        <ul class="space-y-1">
                                            @foreach($question->answers as $answer)
                                                <li class="text-sm {{ $answer->is_correct ? 'text-green-600 font-medium' : 'text-gray-600' }}">
                                                    {{ $answer->answer_text }} {{ $answer->is_correct ? '✓' : '' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('questions.edit', [$quiz->id, $question->id]) }}" class="text-primary hover:underline">{{ __('messages.edit') }}</a>
                                <form action="{{ route('questions.destroy', [$quiz->id, $question->id]) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
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
                <p class="text-gray-500">{{ __('messages.no_questions') }}</p>
            </div>
        @endif
    </div>
@endsection