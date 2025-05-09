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
    
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">{{ $quiz->title }}</h1>
        <p class="text-gray-600 mb-2">{{ $quiz->description }}</p>
        <p class="text-sm text-gray-500 mb-6">{{ __('messages.passing_score') }}: {{ $quiz->passing_score }}%</p>
        
        <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            
            @if($quiz->questions->count() > 0)
                <div class="space-y-8">
                    @foreach($quiz->questions as $index => $question)
                        <div class="border rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                            
                            @if($question->question_type === 'multiple_choice')
                                <div class="space-y-2">
                                    @foreach($question->answers as $answer)
                                        <div class="flex items-center">
                                            <input type="radio" id="question_{{ $question->id }}_answer_{{ $answer->id }}" name="question_{{ $question->id }}" value="{{ $answer->id }}" class="mr-2">
                                            <label for="question_{{ $question->id }}_answer_{{ $answer->id }}">{{ $answer->answer_text }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($question->question_type === 'true_false')
                                <div class="space-y-2">
                                    @foreach($question->answers as $answer)
                                        <div class="flex items-center">
                                            <input type="radio" id="question_{{ $question->id }}_answer_{{ $answer->id }}" name="question_{{ $question->id }}" value="{{ $answer->id }}" class="mr-2">
                                            <label for="question_{{ $question->id }}_answer_{{ $answer->id }}">{{ $answer->answer_text }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($question->question_type === 'text')
                                <div>
                                    <textarea name="question_{{ $question->id }}" rows="3" class="w-full px-4 py-2 border rounded-md" placeholder="{{ __('messages.your_answer') }}"></textarea>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                        {{ __('messages.submit_quiz') }}
                    </button>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">{{ __('messages.no_questions') }}</p>
                </div>
            @endif
        </form>
    </div>
@endsection