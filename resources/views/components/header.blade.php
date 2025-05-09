<header class="bg-white shadow">
    <div class="container mx-auto px-4">
        <nav class="flex items-center justify-between py-4">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                    <span class="ml-2 text-xl font-bold text-primary">{{ config('app.name') }}</span>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary">{{ __('messages.home') }}</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary">{{ __('messages.dashboard') }}</a>
                    <a href="{{ route('courses.index') }}" class="text-gray-700 hover:text-primary">{{ __('messages.courses') }}</a>
                    <a href="{{ route('leaderboard') }}" class="text-gray-700 hover:text-primary">{{ __('messages.leaderboard') }}</a>
                @endauth
                
                <div class="border-l border-gray-300 h-6 mx-2"></div>
                
                @include('components.language-switcher')
                
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">{{ __('messages.register') }}</a>
                @else
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary focus:outline-none">
                            <span class="mr-1">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.profile') }}</a>
                            
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.admin_dashboard') }}</a>
                            @endif
                            
                            @if(Auth::user()->isCompanyAdmin())
                                <a href="{{ route('company.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ __('messages.company_dashboard') }}</a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
            
            <div class="md:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="text-gray-700 hover:text-primary focus:outline-none">
                    <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <div x-show="open" class="absolute top-16 right-0 left-0 bg-white shadow-md py-2 z-10">
                    <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.home') }}</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.dashboard') }}</a>
                        <a href="{{ route('courses.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.courses') }}</a>
                        <a href="{{ route('leaderboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.leaderboard') }}</a>
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.profile') }}</a>
                        
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.admin_dashboard') }}</a>
                        @endif
                        
                        @if(Auth::user()->isCompanyAdmin())
                            <a href="{{ route('company.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.company_dashboard') }}</a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.login') }}</a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('messages.register') }}</a>
                    @endauth
                    
                    <div class="px-4 py-2">
                        @include('components.language-switcher')
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>