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
    
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $course->title }}</h1>
                <p class="text-gray-600">{{ $course->description }}</p>
            </div>
            
            <div class="flex items-center">
                @if($course->creator_id === Auth::id() || Auth::user()->isCompanyAdmin() || Auth::user()->isAdmin())
                    <a href="{{ route('courses.edit', $course->id) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark mr-2">
                        {{ __('messages.edit') }}
                    </a>
                @endif
                
                @if(!$enrollment)
                    <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                            {{ __('messages.enroll') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        @if($enrollment)
            <div class="mb-6">
                <div class="flex justify-between text-sm mb-1">
                    <span>{{ __('messages.progress') }}</span>
                    <span>{{ $enrollment->progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                </div>
            </div>
        @endif
        
        <div class="mb-6">
            <div class="flex items-center text-sm text-gray-500">
                <span class="mr-4">
                    {{ __('messages.course_creator') }}: {{ $course->creator->name }}
                </span>
                <span class="mr-4">
                    {{ __('messages.status') }}: 
                    @if($course->status === 'pending')
                        <span class="text-yellow-500">{{ __('messages.pending_approval') }}</span>
                    @elseif($course->status === 'approved')
                        <span class="text-green-500">{{ __('messages.approved') }}</span>
                    @else
                        <span class="text-red-500">{{ __('messages.rejected') }}</span>
                    @endif
                </span>
                <span>
                    {{ __('messages.points_reward') }}: {{ $course->points_reward }}
                </span>
            </div>
        </div>
        
        @if(Auth::user()->isCompanyAdmin() || Auth::user()->isAdmin())
            @if($course->status === 'pending')
                <div class="flex space-x-2 mb-6">
                    <form action="{{ route('courses.approve', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                            {{ __('messages.approve_course') }}
                        </button>
                    </form>
                    
                    <form action="{{ route('courses.reject', $course->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                            {{ __('messages.reject_course') }}
                        </button>
                    </form>
                </div>
            @endif
        @endif
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('messages.course_content') }}</h2>
                
                @if($course->lessons->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->lessons as $lesson)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold mb-2">{{ $lesson->title }}</h3>
                                
                                @if($lesson->content)
                                    <div class="text-sm text-gray-600 mb-2">
                                        {{ Str::limit($lesson->content, 100) }}
                                    </div>
                                @endif
                                
                                @if($lesson->video_url)
                                    <div class="mb-2">
                                        <span class="text-sm text-primary">{{ __('messages.video_available') }}</span>
                                    </div>
                                @endif
                                
                                <div class="text-right">
                                    <a href="#" class="text-primary hover:underline">{{ __('messages.view') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">{{ __('messages.no_lessons') }}</p>
                        
                        @if($course->creator_id === Auth::id() || Auth::user()->isCompanyAdmin() || Auth::user()->isAdmin())
                            <a href="{{ route('lessons.create', $course->id) }}" class="mt-4 inline-block bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                                {{ __('messages.add_lesson') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('messages.course_quizzes') }}</h2>
                
                @if($course->quizzes->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->quizzes as $quiz)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold mb-2">{{ $quiz->title }}</h3>
                                
                                @if($quiz->description)
                                    <div class="text-sm text-gray-600 mb-2">
                                        {{ Str::limit($quiz->description, 100) }}
                                    </div>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">
                                        {{ __('messages.passing_score') }}: {{ $quiz->passing_score }}%
                                    </span>
                                    
                                    <div>
                                        @if($course->creator_id === Auth::id() || Auth::user()->isCompanyAdmin() || Auth::user()->isAdmin())
                                            <a href="{{ route('quizzes.edit', $quiz->id) }}" class="text-primary hover:underline mr-2">{{ __('messages.edit') }}</a>
                                        @endif
                                        
                                        <a href="{{ route('quizzes.take', $quiz->id) }}" class="text-primary hover:underline">{{ __('messages.take_quiz') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">{{ __('messages.no_quizzes') }}</p>
                        
                        @if($course->creator_id === Auth::id() || Auth::user()->isCompanyAdmin() || Auth::user()->isAdmin())
                            <a href="{{ route('quizzes.create', $course->id) }}" class="mt-4 inline-block bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                                {{ __('messages.add_quiz') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('messages.course_details') }}</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium text-gray-700">{{ __('messages.points_reward') }}</h3>
                        <p>{{ $course->points_reward }}</p>
                    </div>
                    
                    @if($course->category)
                        <div>
                            <h3 class="font-medium text-gray-700">{{ __('messages.course_category') }}</h3>
                            <p>{{ $course->category->name }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <h3 class="font-medium text-gray-700">{{ __('messages.course_creator') }}</h3>
                        <p>{{ $course->creator->name }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700">{{ __('messages.date') }}</h3>
                        <p>{{ $course->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            
            @if($enrollment && $enrollment->completion_status === 'completed')
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-center">
                        <div class="text-green-500 mb-2">
                            <svg class="h-12 w-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('messages.course_completed') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('messages.points_earned') }}: {{ $course->points_reward }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection