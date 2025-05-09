<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isCompanyAdmin()) {
            return redirect()->route('company.dashboard');
        }
        
        // Get enrolled courses
        $enrolledCourses = $user->enrolledCourses()
            ->with('creator')
            ->take(4)
            ->get();
        
        // Get active challenges
        $activeChallenges = Challenge::where('company_id', $user->company_id)
            ->where('deadline', '>', now())
            ->take(3)
            ->get();
        
        // Get leaderboard
        $leaderboard = User::where('company_id', $user->company_id)
            ->orderBy('points', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard', compact('user', 'enrolledCourses', 'activeChallenges', 'leaderboard'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        
        return view('profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'language_preference' => 'required|string|in:en,ar',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user->name = $request->name;
        $user->language_preference = $request->language_preference;
        
        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }
        
        $user->save();
        
        return redirect()->route('profile')->with('success', __('messages.profile_updated'));
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => __('messages.current_password_incorrect')]);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile')->with('success', __('messages.password_updated'));
    }
    
    public function leaderboard()
    {
        $user = Auth::user();
        
        $leaderboard = User::where('company_id', $user->company_id)
            ->orderBy('points', 'desc')
            ->paginate(20);
        
        return view('leaderboard', compact('leaderboard', 'user'));
    }
}