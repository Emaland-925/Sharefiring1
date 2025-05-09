@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold mb-2">{{ __('messages.welcome_user', ['name' => Auth::user()->name]) }}</h1>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="col-span-1">
            @include('components.points-badge', ['points' => $user->points, 'level' => $user->level])
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">{{ __('messages.my_courses') }}</h2>
                    <a href="{{ route('courses.index') }}" class="text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                
                @if($enrolledCourses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($enrolledCourses as $course)
                            @include('components.course-card', ['course' => $course, 'enrollment' => $course->pivot])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">{{ __('messages.no_courses_enrolled') }}</p>
                        <a href="{{ route('courses.index') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                            {{ __('messages.browse_courses') }}
                        </a>
                    </div>
                @endif
            </div>
            
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">{{ __('messages.active_challenges') }}</h2>
                    <a href="#" class="text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                
                @if($activeChallenges->count() > 0)
                    <div class="space-y-4">
                        @foreach($activeChallenges as $challenge)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold mb-1">{{ $challenge->title }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($challenge->description, 100) }}</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">
                                        {{ __('messages.days_left', ['days' => now()->diffInDays($challenge->deadline)]) }}
                                    </span>
                                    <span class="bg-primary-light text-primary px-2 py-1 rounded-full">
                                        {{ $challenge->points_reward }} {{ __('messages.points') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">{{ __('messages.no_challenges') }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">{{ __('messages.leaderboard') }}</h2>
                    <a href="{{ route('leaderboard') }}" class="text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                
                <div class="space-y-2">
                    @foreach($leaderboard as $index => $leader)
                        @include('components.leaderboard-item', [
                            'rank' => $index + 1,
                            'user' => $leader,
                            'isCurrentUser' => $leader->id === $user->id
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection