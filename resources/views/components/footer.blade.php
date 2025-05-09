<footer class="bg-white shadow-inner mt-8 py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/3 mb-6 md:mb-0">
                <a href="{{ route('home') }}" class="flex items-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                    <span class="ml-2 text-xl font-bold text-primary">{{ config('app.name') }}</span>
                </a>
                <p class="text-gray-600 mb-4">{{ __('messages.hero_description') }}</p>
            </div>
            
            <div class="w-full md:w-1/3 mb-6 md:mb-0">
                <h3 class="text-lg font-semibold mb-4">{{ __('messages.quick_links') }}</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-primary">{{ __('messages.home') }}</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-primary">{{ __('messages.dashboard') }}</a></li>
                        <li><a href="{{ route('courses.index') }}" class="text-gray-600 hover:text-primary">{{ __('messages.courses') }}</a></li>
                        <li><a href="{{ route('leaderboard') }}" class="text-gray-600 hover:text-primary">{{ __('messages.leaderboard') }}</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-primary">{{ __('messages.login') }}</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-primary">{{ __('messages.register') }}</a></li>
                    @endauth
                </ul>
            </div>
            
            <div class="w-full md:w-1/3">
                <h3 class="text-lg font-semibold mb-4">{{ __('messages.contact_us') }}</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-600">info@sharefiring.com</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-600">+1 (123) 456-7890</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-200 mt-8 pt-6 text-center">
            <p class="text-gray-600">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.footer_rights') }}</p>
        </div>
    </div>
</footer>