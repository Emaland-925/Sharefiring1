@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="py-16 bg-gradient-to-r from-primary-light to-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ __('messages.hero_title') }}</h1>
                    <h2 class="text-2xl font-semibold text-primary mb-4">{{ __('messages.hero_subtitle') }}</h2>
                    <p class="text-lg text-gray-700 mb-8">{{ __('messages.hero_description') }}</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark">
                            {{ __('messages.register') }}
                        </a>
                        <a href="#why-us" class="border border-primary text-primary px-6 py-3 rounded-md hover:bg-primary-light">
                            {{ __('messages.learn_more') }}
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <img src="{{ asset('images/hero-image.png') }}" alt="ShareFiring Hero" class="w-full h-auto">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us Section -->
    <section id="why-us" class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">{{ __('messages.why_choose_us') }}</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="bg-primary-light p-3 rounded-full mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.reason1_title') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.reason1_description') }}</p>
                    <a href="{{ route('register') }}" class="mt-auto text-primary border border-primary px-4 py-2 rounded hover:bg-primary-light">
                        {{ __('messages.try_now') }}
                    </a>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="bg-primary-light p-3 rounded-full mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.reason2_title') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.reason2_description') }}</p>
                    <a href="{{ route('register') }}" class="mt-auto text-primary border border-primary px-4 py-2 rounded hover:bg-primary-light">
                        {{ __('messages.try_now') }}
                    </a>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="bg-primary-light p-3 rounded-full mb-4">
                        <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.reason3_title') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.reason3_description') }}</p>
                    <a href="{{ route('register') }}" class="mt-auto text-primary border border-primary px-4 py-2 rounded hover:bg-primary-light">
                        {{ __('messages.try_now') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">{{ __('messages.how_it_works') }}</h2>
                <div class="w-24 h-1 bg-primary mx-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-primary text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('messages.step1_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.step1_description') }}</p>
                </div>
                
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-primary text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('messages.step2_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.step2_description') }}</p>
                </div>
                
                <div class="bg-white p-8 rounded-lg shadow-md text-center">
                    <div class="bg-primary-light w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-primary text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('messages.step3_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.step3_description') }}</p>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark">
                    {{ __('messages.try_now') }}
                </a>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">{{ __('messages.contact_us') }}</h2>
                <div class="w-24 h-1 bg-white mx-auto"></div>
            </div>
            
            <div class="max-w-lg mx-auto">
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block mb-2">{{ __('messages.contact_name') }}</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 rounded-md bg-white text-gray-900" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block mb-2">{{ __('messages.contact_email') }}</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded-md bg-white text-gray-900" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="block mb-2">{{ __('messages.contact_phone') }}</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 rounded-md bg-white text-gray-900">
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block mb-2">{{ __('messages.contact_message') }}</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 rounded-md bg-white text-gray-900" required></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-white text-primary px-4 py-2 rounded-md hover:bg-gray-100">
                        {{ __('messages.contact_submit') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection