@extends('layouts.company')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.company_dashboard') }}</h1>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('messages.total_employees') }}</h2>
                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $totalEmployees }}</p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('messages.active_courses') }}</h2>
                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $activeCourses }}</p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('messages.completed_courses') }}</h2>
                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $completedCourses }}</p>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('messages.average_progress') }}</h2>
                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ number_format($averageProgress, 0) }}%</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">{{ __('messages.top_performers') }}</h2>
                    <a href="{{ route('leaderboard') }}" class="text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                
                @if(count($topEmployees) > 0)
                    <div class="space-y-2">
                        @foreach($topEmployees as $index => $employee)
                            @include('components.leaderboard-item', [
                                'rank' => $index + 1,
                                'user' => $employee,
                                'isCurrentUser' => false
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">{{ __('messages.no_data') }}</p>
                    </div>
                @endif
            </div>
            
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('messages.quick_actions') }}</h2>
                
                <div class="space-y-3">
                    <a href="{{ route('company.employees.create') }}" class="block w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark text-center">
                        {{ __('messages.add_employee') }}
                    </a>
                    
                    <a href="{{ route('courses.create') }}" class="block w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark text-center">
                        {{ __('messages.create_course') }}
                    </a>
                    
                    <a href="{{ route('company.settings') }}" class="block w-full border border-primary text-primary py-2 px-4 rounded-md hover:bg-primary-light text-center">
                        {{ __('messages.company_settings') }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold">{{ __('messages.courses') }}</h2>
                    <a href="{{ route('courses.index') }}" class="text-primary hover:underline">{{ __('messages.view_all') }}</a>
                </div>
                
                @if(count($courses) > 0)
                    <div class="space-y-4">
                        @foreach($courses as $course)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold mb-1">{{ $course->title }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($course->description, 100) }}</p>
                                        <div class="flex items-center text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                @if($course->status === 'approved')
                                                    {{ __('messages.approved') }}
                                                @elseif($course->status === 'rejected')
                                                    {{ __('messages.rejected') }}
                                                @else
                                                    {{ __('messages.pending_approval') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('courses.show', $course->id) }}" class="text-primary hover:underline">
                                        {{ __('messages.view') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">{{ __('messages.no_courses') }}</p>
                        <a href="{{ route('courses.create') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
                            {{ __('messages.create_course') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection