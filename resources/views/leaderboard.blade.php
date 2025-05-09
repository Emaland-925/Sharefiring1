@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.leaderboard') }}</h1>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <div class="relative">
                <input type="text" id="search" class="w-full px-4 py-2 pl-10 border rounded-md" placeholder="{{ __('messages.leaderboard.search') }}">
                <svg class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        
        <div id="leaderboardList" class="space-y-2">
            @foreach($leaderboard as $index => $leader)
                @include('components.leaderboard-item', [
                    'rank' => $index + 1,
                    'user' => $leader,
                    'isCurrentUser' => $leader->id === $user->id
                ])
            @endforeach
        </div>
        
        <div id="noResults" class="hidden text-center py-8">
            <p class="text-gray-500">{{ __('messages.leaderboard.noSearchResults') }}</p>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const leaderboardItems = document.querySelectorAll('#leaderboardList > div');
        const noResults = document.getElementById('noResults');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let hasResults = false;
            
            leaderboardItems.forEach(item => {
                const name = item.querySelector('p.font-medium').textContent.toLowerCase();
                
                if (name.includes(searchTerm)) {
                    item.classList.remove('hidden');
                    hasResults = true;
                } else {
                    item.classList.add('hidden');
                }
            });
            
            if (hasResults) {
                noResults.classList.add('hidden');
            } else {
                noResults.classList.remove('hidden');
            }
        });
    });
</script>
@endsection