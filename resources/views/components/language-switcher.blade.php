<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary focus:outline-none">
        <span class="mr-1">{{ app()->getLocale() == 'en' ? 'English' : 'العربية' }}</span>
        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
        <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">English</a>
        <a href="{{ route('language.switch', 'ar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">العربية</a>
    </div>
</div>