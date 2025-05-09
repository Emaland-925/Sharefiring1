<div class="flex items-center p-3 rounded-lg {{ $isCurrentUser ? 'bg-primary-light' : 'hover:bg-gray-50' }}">
    <div class="flex items-center justify-center w-8 h-8 rounded-full mr-4">
        @if($rank <= 3)
            <span class="text-xl">
                @if($rank === 1) 🥇 @elseif($rank === 2) 🥈 @else 🥉 @endif
            </span>
        @else
            <span class="text-gray-500 font-medium">{{ $rank }}</span>
        @endif
    </div>
    
    <div class="flex-shrink-0 mr-3">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full">
        @else
            <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-primary font-medium">
                {{ substr($user->name, 0, 1) }}
            </div>
        @endif
    </div>
    
    <div class="flex-grow">
        <p class="font-medium">{{ $user->name }}</p>
    </div>
    
    <div class="flex items-center ml-auto">
        <div class="px-2 py-1 rounded bg-primary-light text-primary text-xs font-medium mr-2">
            {{ __('messages.level') }} {{ $user->level }}
        </div>
        <div class="text-gray-700 font-medium">
            {{ $user->points }} {{ __('messages.points') }}
        </div>
    </div>
</div>