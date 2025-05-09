<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and has a language preference
        if (Auth::check() && Auth::user()->language_preference) {
            App::setLocale(Auth::user()->language_preference);
        } 
        // Check if there's a session language
        elseif (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Check if there's a language parameter in the URL
        elseif ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['en', 'ar'])) {
                Session::put('locale', $locale);
                App::setLocale($locale);
            }
        }
        
        return $next($request);
    }
}