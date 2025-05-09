@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('quizzes.edit', $quiz->id) }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.add_question') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('messages.quiz') }}: {{ $quiz->title }}</p>
        
        <form action="{{ route('questions.store', $quiz->id) }}" method="POST" id="questionForm">
            @csrf
            
            <div class="mb-4">
                <label for="question_text" class="block text-gray-700 mb-2">{{ __('messages.question_text') }}</label>
                <textarea id="question_text" class="w-full px-4 py-2 border rounded-md @error('question_text') border-red-500 @enderror" name="question_text" rows="3" required>{{ old('question_text') }}</textarea>
                
                @error('question_text')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="question_type" class="block text-gray-700 mb-2">{{ __('messages.question_type') }}</label>
                <select id="question_type" name="question_type" class="w-full px-4 py-2 border rounded-md @error('question_type') border-red-500 @enderror" required>
                    <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>{{ __('messages.multiple_choice') }}</option>
                    <option value="true_false" {{ old('question_type') == 'true_false' ? 'selected' : '' }}>{{ __('messages.true_false') }}</option>
                    <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>{{ __('messages.text_answer') }}</option>
                </select>
                
                @error('question_type')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="points" class="block text-gray-700 mb-2">{{ __('messages.question_points') }}</label>
                <input id="points" type="number" class="w-full px-4 py-2 border rounded-md @error('points') border-red-500 @enderror" name="points" value="{{ old('points', 1) }}" min="1" required>
                
                @error('points')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div id="answersContainer" class="mb-6">
                <label class="block text-gray-700 mb-2">{{ __('messages.answers') }}</label>
                
                <div id="multipleChoiceAnswers">
                    <div class="answer-item mb-2 border rounded-md p-4">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="answers[0][is_correct]" id="answer_correct_0" class="mr-2">
                            <label for="answer_correct_0" class="text-sm font-medium">{{ __('messages.is_correct') }}</label>
                        </div>
                        <textarea name="answers[0][answer_text]" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="{{ __('messages.answer_text') }}" required></textarea>
                    </div>
                    
                    <div class="answer-item mb-2 border rounded-md p-4">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="answers[1][is_correct]" id="answer_correct_1" class="mr-2">
                            <label for="answer_correct_1" class="text-sm font-medium">{{ __('messages.is_correct') }}</label>
                        </div>
                        <textarea name="answers[1][answer_text]" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="{{ __('messages.answer_text') }}" required></textarea>
                    </div>
                </div>
                
                <div id="trueFalseAnswers" class="hidden">
                    <div class="answer-item mb-2 border rounded-md p-4">
                        <div class="flex items-center mb-2">
                            <input type="radio" name="tf_correct" value="true" id="answer_true" class="mr-2" checked>
                            <label for="answer_true" class="text-sm font-medium">{{ __('messages.is_correct') }}</label>
                        </div>
                        <input type="hidden" name="tf_answers[0][answer_text]" value="True">
                    </div>
                    
                    <div class="answer-item mb-2 border rounded-md p-4">
                        <div class="flex items-center mb-2">
                            <input type="radio" name="tf_correct" value="false" id="answer_false" class="mr-2">
                            <label for="answer_false" class="text-sm font-medium">{{ __('messages.is_correct') }}</label>
                        </div>
                        <input type="hidden" name="tf_answers[1][answer_text]" value="False">
                    </div>
                </div>
                
                <div id="textAnswer" class="hidden">
                    <p class="text-sm text-gray-600 mb-2">{{ __('messages.text_answer_description') }}</p>
                </div>
                
                <button type="button" id="addAnswerBtn" class="mt-2 text-primary hover:underline">
                    + {{ __('messages.add_answer') }}
                </button>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.create') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionType = document.getElementById('question_type');
        const multipleChoiceAnswers = document.getElementById('multipleChoiceAnswers');
        const trueFalseAnswers = document.getElementById('trueFalseAnswers');
        const textAnswer = document.getElementById('textAnswer');
        const addAnswerBtn = document.getElementById('addAnswerBtn');
        
        let answerCount = 2;
        
        // Handle question type change
        questionType.addEventListener('change', function() {
            if (this.value === 'multiple_choice') {
                multipleChoiceAnswers.classList.remove('hidden');
                trueFalseAnswers.classList.add('hidden');
                textAnswer.classList.add('hidden');
                addAnswerBtn.classList.remove('hidden');
            } else if (this.value === 'true_false') {
                multipleChoiceAnswers.classList.add('hidden');
                trueFalseAnswers.classList.remove('hidden');
                textAnswer.classList.add('hidden');
                addAnswerBtn.classList.add('hidden');
            } else if (this.value === 'text') {
                multipleChoiceAnswers.classList.add('hidden');
                trueFalseAnswers.classList.add('hidden');
                textAnswer.classList.remove('hidden');
                addAnswerBtn.classList.add('hidden');
            }
        });
        
        // Add new answer
        addAnswerBtn.addEventListener('click', function() {
            const newAnswer = document.createElement('div');
            newAnswer.className = 'answer-item mb-2 border rounded-md p-4';
            newAnswer.innerHTML = `
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="answers[${answerCount}][is_correct]" id="answer_correct_${answerCount}" class="mr-2">
                    <label for="answer_correct_${answerCount}" class="text-sm font-medium">${questionType.value === 'true_false' ? 'True' : '{{ __('messages.is_correct') }}'}</label>
                </div>
                <textarea name="answers[${answerCount}][answer_text]" class="w-full px-4 py-2 border rounded-md" rows="2" placeholder="{{ __('messages.answer_text') }}" required></textarea>
                <button type="button" class="remove-answer mt-2 text-red-500 hover:underline">{{ __('messages.remove') }}</button>
            `;
            
            multipleChoiceAnswers.appendChild(newAnswer);
            answerCount++;
            
            // Add event listener to remove button
            newAnswer.querySelector('.remove-answer').addEventListener('click', function() {
                multipleChoiceAnswers.removeChild(newAnswer);
            });
        });
        
        // Handle form submission
        document.getElementById('questionForm').addEventListener('submit', function(e) {
            if (questionType.value === 'true_false') {
                // Set the correct answer for true/false questions
                const trueCorrect = document.getElementById('answer_true').checked;
                document.querySelector('input[name="tf_answers[0][is_correct]"]').value = trueCorrect;
                document.querySelector('input[name="tf_answers[1][is_correct]"]').value = !trueCorrect;
            }
        });
    });
</script>
@endsection