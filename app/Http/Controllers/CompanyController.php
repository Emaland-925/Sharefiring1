<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function register()
    {
        return view('auth.company-register');
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'theme' => 'required|string|in:orange,blue,green,purple,red',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create user with company role
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'company',
        ]);
        
        // Create company
        $company = Company::create([
            'name' => $request->company_name,
            'description' => $request->company_description,
            'theme' => $request->theme,
            'admin_id' => $user->id,
        ]);
        
        // Update user with company_id
        $user->company_id = $company->id;
        $user->save();
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company_logos', 'public');
            $company->logo = $path;
            $company->save();
        }
        
        Auth::login($user);
        
        return redirect()->route('company.dashboard')->with('success', __('messages.company_registration_success'));
    }
    
    public function dashboard()
    {
        $user = Auth::user();
        $company = $user->managedCompany;
        
        if (!$company) {
            return redirect()->route('home')->with('error', __('messages.unauthorized_access'));
        }
        
        $totalEmployees = User::where('company_id', $company->id)->count();
        $activeCourses = $company->courses()->where('status', 'approved')->count();
        $completedCourses = $company->courses()->whereHas('enrollments', function($query) {
            $query->where('completion_status', 'completed');
        })->count();
        
        // Calculate average progress
        $enrollments = $company->courses()->with('enrollments')->get()->pluck('enrollments')->flatten();
        $averageProgress = $enrollments->count() > 0 ? $enrollments->avg('progress') : 0;
        
        return view('company.dashboard', compact('company', 'totalEmployees', 'activeCourses', 'completedCourses', 'averageProgress'));
    }
    
    public function settings()
    {
        $user = Auth::user();
        $company = $user->managedCompany;
        
        if (!$company) {
            return redirect()->route('home')->with('error', __('messages.unauthorized_access'));
        }
        
        return view('company.settings', compact('company'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->managedCompany;
        
        if (!$company) {
            return redirect()->route('home')->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'theme' => 'required|string|in:orange,blue,green,purple,red',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $company->name = $request->name;
        $company->description = $request->description;
        $company->theme = $request->theme;
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $path = $request->file('logo')->store('company_logos', 'public');
            $company->logo = $path;
        }
        
        $company->save();
        
        return redirect()->route('company.settings')->with('success', __('messages.company_updated'));
    }
    
    public function employees()
    {
        $user = Auth::user();
        $company = $user->managedCompany;
        
        if (!$company) {
            return redirect()->route('home')->with('error', __('messages.unauthorized_access'));
        }
        
        $employees = User::where('company_id', $company->id)->paginate(10);
        
        return view('company.employees', compact('company', 'employees'));
    }
    
    public function addEmployee()
    {
        $user = Auth::user();
        $company = $user->managedCompany;
        
        if (!$company) {
            return redirect()->route('home')->with('error', __('messages.unauthorized_access'));
        }
        
        return view('company.add-employee', compact('company'));
    }
    
    public function storeEmployee(Request $request)
    {
        $user = Auth::user();
        $company = $user->managedCompany;
        
        if (!$company) {
            return redirect()->route('home')->with('error', __('messages.unauthorized_access'));
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create employee
        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'company_id' => $company->id,
        ]);
        
        return redirect()->route('company.employees')->with('success', __('messages.employee_added'));
    }
}