@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('messages.courses') }}</h1>
        
        <a href="{{ route('courses.create') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
            {{ __('messages.create_course') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('messages.my_courses') }}</h2>
            
            @if($enrolledCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrolledCourses as $course)
                        @include('components.course-card', ['course' => $course, 'enrollment' => $course->pivot])
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">{{ __('messages.no_courses_enrolled') }}</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('messages.available_courses') }}</h2>
            
            @if($availableCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($availableCourses as $course)
                        @include('components.course-card', ['course' => $course])
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">{{ __('messages.no_courses_available') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection