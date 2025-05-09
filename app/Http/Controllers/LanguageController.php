<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        // Check if the language is supported
        if (in_array($lang, ['en', 'ar'])) {
            // Store the language preference in session
            Session::put('locale', $lang);
            
            // If user is logged in, update their language preference
            if (Auth::check()) {
                $user = Auth::user();
                $user->language_preference = $lang;
                $user->save();
            }
            
            return redirect()->back();
        }
        
        return redirect()->back()->with('error', 'Language not supported.');
    }
}