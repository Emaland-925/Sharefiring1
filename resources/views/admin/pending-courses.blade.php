@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.pending_approvals') }}</h1>
        
        <a href="{{ route('admin.courses') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
            {{ __('messages.all_courses') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        @if($courses->count() > 0)
            <div class="space-y-6">
                @foreach($courses as $course)
                    <div class="border rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold mb-2">{{ $course->title }}</h2>
                                <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('messages.company') }}</p>
                                        <p class="font-medium">{{ $course->company->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('messages.course_creator') }}</p>
                                        <p class="font-medium">{{ $course->creator->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('messages.date') }}</p>
                                        <p class="font-medium">{{ $course->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">{{ __('messages.points_reward') }}</p>
                                        <p class="font-medium">{{ $course->points_reward }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-4">
                                    <a href="{{ route('courses.show', $course->id) }}" class="text-primary hover:underline">
                                        {{ __('messages.view_details') }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                <form action="{{ route('courses.approve', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                        {{ __('messages.approve') }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('courses.reject', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        {{ __('messages.reject') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">{{ __('messages.no_pending_approvals') }}</p>
            </div>
        @endif
    </div>
@endsection