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
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.quiz_results') }}</h1>
        <p class="text-gray-600 mb-6">{{ $quiz->title }}</p>
        
        <div class="flex justify-center mb-8">
            <div class="w-48 h-48 relative">
                <svg viewBox="0 0 36 36" class="w-full h-full">
                    <path class="stroke-current text-gray-200" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="{{ $passed ? 'stroke-current text-green-500' : 'stroke-current text-red-500' }}" stroke-width="3" fill="none" stroke-dasharray="{{ $score }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <text x="18" y="20.35" class="text-3xl font-bold" text-anchor="middle">{{ number_format($score, 0) }}%</text>
                </svg>
            </div>
        </div>
        
        <div class="text-center mb-8">
            @if($passed)
                <div class="text-green-500 mb-2">
                    <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-600 mb-2">{{ __('messages.quiz_passed') }}</h2>
            @else
                <div class="text-red-500 mb-2">
                    <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-red-600 mb-2">{{ __('messages.quiz_failed') }}</h2>
            @endif
            
            <p class="text-gray-600">
                {{ __('messages.quiz_score') }}: {{ number_format($score, 0) }}% ({{ $earnedPoints }}/{{ $totalPoints }} {{ __('messages.points') }})
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-semibold text-green-700 mb-2">{{ __('messages.correct_answers') }}</h3>
                <p class="text-green-600">{{ $userAnswers->where('is_correct', true)->count() }} / {{ $quiz->questions->count() }}</p>
            </div>
            
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="font-semibold text-red-700 mb-2">{{ __('messages.incorrect_answers') }}</h3>
                <p class="text-red-600">{{ $userAnswers->where('is_correct', false)->count() }} / {{ $quiz->questions->count() }}</p>
            </div>
        </div>
        
        @if(!$passed)
            <div class="mt-8 text-center">
                <a href="{{ route('quizzes.take', $quiz->id) }}" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.try_again') }}
                </a>
            </div>
        @endif
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-6">{{ __('messages.detailed_results') }}</h2>
        
        <div class="space-y-6">
            @foreach($quiz->questions as $index => $question)
                @php
                    $userAnswer = $userAnswers->where('question_id', $question->id)->first();
                    $isCorrect = $userAnswer && $userAnswer->is_correct;
                @endphp
                
                <div class="border rounded-lg p-4 {{ $isCorrect ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                    <h3 class="font-semibold mb-2">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                    
                    @if($question->question_type !== 'text')
                        <div class="mt-2">
                            <p class="font-medium {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                                {{ __('messages.your_answer') }}: 
                                @if($userAnswer && $userAnswer->answer)
                                    {{ $userAnswer->answer->answer_text }}
                                @else
                                    {{ __('messages.no_answer') }}
                                @endif
                            </p>
                            
                            @if(!$isCorrect)
                                <p class="mt-1 text-green-600 font-medium">
                                    {{ __('messages.correct_answer') }}: 
                                    {{ $question->answers->where('is_correct', true)->first()->answer_text ?? '' }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="mt-2">
                            <p class="font-medium">
                                {{ __('messages.your_answer') }}: 
                                {{ $userAnswer ? $userAnswer->text_answer : __('messages.no_answer') }}
                            </p>
                            <p class="mt-1 text-gray-600 text-sm">
                                {{ __('messages.text_answer_grading') }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection