<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function show($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $course = $quiz->course;
        
        // Check if user is enrolled
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment && !$user->isAdmin() && $course->creator_id !== $user->id) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.not_enrolled'));
        }
        
        // Check if user has already taken the quiz
        $userAnswers = UserAnswer::where('user_id', $user->id)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->get();
        
        $hasCompletedQuiz = $userAnswers->count() > 0;
        
        return view('quizzes.show', compact('quiz', 'course', 'userAnswers', 'hasCompletedQuiz'));
    }
    
    public function edit($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        return view('quizzes.edit', compact('quiz', 'course'));
    }
    
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update quiz
        $quiz->title = $request->title;
        $quiz->description = $request->description;
        $quiz->passing_score = $request->passing_score ?? $quiz->passing_score;
        $quiz->save();
        
        return redirect()->route('quizzes.edit', $quiz->id)->with('success', __('messages.quiz_updated'));
    }
    
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        // Delete quiz
        $quiz->delete();
        
        return redirect()->route('courses.edit', $course->id)->with('success', __('messages.quiz_deleted'));
    }
    
    public function addQuestion($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        return view('quizzes.add-question', compact('quiz', 'course'));
    }
    
    public function storeQuestion(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:multiple_choice,true_false,text',
            'points' => 'nullable|integer|min:1',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create question
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'points' => $request->points ?? 1,
        ]);
        
        // Create answers
        if ($request->has('answers')) {
            foreach ($request->answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => isset($answerData['is_correct']) ? true : false,
                ]);
            }
        }
        
        return redirect()->route('quizzes.edit', $quiz->id)->with('success', __('messages.question_added'));
    }
    
    public function editQuestion($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::where('quiz_id', $quizId)->with('answers')->findOrFail($questionId);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        return view('quizzes.edit-question', compact('quiz', 'question', 'course'));
    }
    
    public function updateQuestion(Request $request, $quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::where('quiz_id', $quizId)->findOrFail($questionId);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:multiple_choice,true_false,text',
            'points' => 'nullable|integer|min:1',
            'answers.*.id' => 'nullable|exists:answers,id',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update question
        $question->question_text = $request->question_text;
        $question->question_type = $request->question_type;
        $question->points = $request->points ?? $question->points;
        $question->save();
        
        // Update or create answers
        if ($request->has('answers')) {
            // Get existing answer IDs
            $existingAnswerIds = $question->answers->pluck('id')->toArray();
            $updatedAnswerIds = [];
            
            foreach ($request->answers as $answerData) {
                if (isset($answerData['id'])) {
                    // Update existing answer
                    $answer = Answer::find($answerData['id']);
                    if ($answer) {
                        $answer->answer_text = $answerData['answer_text'];
                        $answer->is_correct = isset($answerData['is_correct']) ? true : false;
                        $answer->save();
                        $updatedAnswerIds[] = $answer->id;
                    }
                } else {
                    // Create new answer
                    $answer = Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $answerData['answer_text'],
                        'is_correct' => isset($answerData['is_correct']) ? true : false,
                    ]);
                    $updatedAnswerIds[] = $answer->id;
                }
            }
            
            // Delete answers that were not updated
            $answersToDelete = array_diff($existingAnswerIds, $updatedAnswerIds);
            Answer::whereIn('id', $answersToDelete)->delete();
        }
        
        return redirect()->route('quizzes.edit', $quiz->id)->with('success', __('messages.question_updated'));
    }
    
    public function destroyQuestion($quizId, $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = Question::where('quiz_id', $quizId)->findOrFail($questionId);
        $course = $quiz->course;
        
        // Check if user is the creator or company admin
        $user = Auth::user();
        if ($course->creator_id !== $user->id && !$user->isCompanyAdmin() && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.unauthorized_access'));
        }
        
        // Delete question (answers will be deleted by cascade)
        $question->delete();
        
        return redirect()->route('quizzes.edit', $quiz->id)->with('success', __('messages.question_deleted'));
    }
    
    public function take($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $course = $quiz->course;
        
        // Check if user is enrolled
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment && !$user->isAdmin() && $course->creator_id !== $user->id) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.not_enrolled'));
        }
        
        // Check if user has already taken the quiz
        $userAnswers = UserAnswer::where('user_id', $user->id)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->get();
        
        if ($userAnswers->count() > 0) {
            return redirect()->route('quizzes.results', $quiz->id)->with('info', __('messages.already_taken_quiz'));
        }
        
        return view('quizzes.take', compact('quiz', 'course'));
    }
    
    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $course = $quiz->course;
        
        // Check if user is enrolled
        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment && !$user->isAdmin() && $course->creator_id !== $user->id) {
            return redirect()->route('courses.show', $course->id)->with('error', __('messages.not_enrolled'));
        }
        
        // Check if user has already taken the quiz
        $existingAnswers = UserAnswer::where('user_id', $user->id)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->count();
        
        if ($existingAnswers > 0) {
            return redirect()->route('quizzes.results', $quiz->id)->with('info', __('messages.already_taken_quiz'));
        }
        
        // Process answers
        $totalPoints = 0;
        $earnedPoints = 0;
        
        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            
            if ($question->question_type === 'text') {
                // For text questions, store the answer but don't mark it correct/incorrect automatically
                UserAnswer::create([
                    'user_id' => $user->id,
                    'question_id' => $question->id,
                    'text_answer' => $request->input('question_' . $question->id),
                    'is_correct' => false, // Needs manual grading
                ]);
            } else {
                // For multiple choice or true/false questions
                $answerId = $request->input('question_' . $question->id);
                $answer = Answer::find($answerId);
                
                if ($answer && $answer->question_id === $question->id) {
                    UserAnswer::create([
                        'user_id' => $user->id,
                        'question_id' => $question->id,
                        'answer_id' => $answerId,
                        'is_correct' => $answer->is_correct,
                    ]);
                    
                    if ($answer->is_correct) {
                        $earnedPoints += $question->points;
                    }
                } else {
                    // No answer selected
                    UserAnswer::create([
                        'user_id' => $user->id,
                        'question_id' => $question->id,
                        'is_correct' => false,
                    ]);
                }
            }
        }
        
        // Calculate score
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $passed = $score >= $quiz->passing_score;
        
        // Update enrollment progress if passed
        if ($passed && $enrollment) {
            // Calculate course progress
            $totalLessons = $course->lessons()->count();
            $totalQuizzes = $course->quizzes()->count();
            $total = $totalLessons + $totalQuizzes;
            
            if ($total > 0) {
                // Assuming each completed quiz and lesson contributes equally to progress
                $completedLessons = 0; // You would need to track this separately
                $completedQuizzes = 1; // This quiz
                
                $progress = (($completedLessons + $completedQuizzes) / $total) * 100;
                $enrollment->progress = $progress;
                
                // Check if course is completed
                if ($progress >= 100) {
                    $enrollment->completion_status = 'completed';
                    
                    // Award points to user
                    $user->points += $course->points_reward;
                    $user->save();
                    
                    // Award points to course creator
                    $creator = User::find($course->creator_id);
                    if ($creator) {
                        $creator->points += $course->points_reward;
                        $creator->save();
                    }
                }
                
                $enrollment->save();
            }
        }
        
        return redirect()->route('quizzes.results', $quiz->id)->with('success', __('messages.quiz_submitted'));
    }
    
    public function results($id)
    {
        $quiz = Quiz::with(['questions.answers'])->findOrFail($id);
        $course = $quiz->course;
        $user = Auth::user();
        
        // Get user answers
        $userAnswers = UserAnswer::where('user_id', $user->id)
            ->whereIn('question_id', $quiz->questions->pluck('id'))
            ->get();
        
        if ($userAnswers->count() === 0) {
            return redirect()->route('quizzes.take', $quiz->id)->with('info', __('messages.take_quiz_first'));
        }
        
        // Calculate score
        $totalPoints = $quiz->questions->sum('points');
        $earnedPoints = 0;
        
        foreach ($userAnswers as $userAnswer) {
            if ($userAnswer->is_correct) {
                $earnedPoints += $userAnswer->question->points;
            }
        }
        
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $passed = $score >= $quiz->passing_score;
        
        return view('quizzes.results', compact('quiz', 'course', 'userAnswers', 'score', 'passed', 'totalPoints', 'earnedPoints'));
    }
}