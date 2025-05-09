<div class="bg-white shadow rounded-lg overflow-hidden h-full flex flex-col">
    <div class="p-6 flex-grow">
        <h3 class="text-lg font-semibold mb-2">{{ $course->title }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
        
        @if(isset($enrollment) && $enrollment)
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1">
                    <span>{{ __('messages.progress') }}</span>
                    <span>{{ $enrollment->progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                </div>
            </div>
        @endif
        
        @if($course->creator)
            <div class="mt-4 text-sm text-gray-500">
                {{ __('messages.course_creator') }}: {{ $course->creator->name }}
            </div>
        @endif
    </div>
    
    <div class="px-6 pb-6">
        @if(!isset($enrollment) || !$enrollment)
            <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded hover:bg-primary-dark">
                    {{ __('messages.enroll') }}
                </button>
            </form>
        @else
            <a href="{{ route('courses.show', $course->id) }}" class="block w-full bg-primary text-white py-2 px-4 rounded hover:bg-primary-dark text-center">
                @if($enrollment->completion_status === 'completed')
                    {{ __('messages.view') }}
                @else
                    {{ __('messages.continue') }}
                @endif
            </a>
        @endif
    </div>
</div>