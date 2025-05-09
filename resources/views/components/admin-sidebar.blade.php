<div class="bg-white shadow rounded-lg p-4 admin-sidebar">
    <h3 class="text-lg font-semibold mb-4">{{ __('messages.admin_panel') }}</h3>
    
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>{{ __('messages.dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="flex items-center p-2 rounded-md {{ request()->routeIs('admin.users*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>{{ __('messages.user_management') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.companies') }}" class="flex items-center p-2 rounded-md {{ request()->routeIs('admin.companies*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>{{ __('messages.company_management') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses') }}" class="flex items-center p-2 rounded-md {{ request()->routeIs('admin.courses') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>{{ __('messages.course_management') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.pending') }}" class="flex items-center p-2 rounded-md {{ request()->routeIs('admin.courses.pending') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>{{ __('messages.pending_approvals') }}</span>
                </a>
            </li>
        </ul>
    </nav>
</div>