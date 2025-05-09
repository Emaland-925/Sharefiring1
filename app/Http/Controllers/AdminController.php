<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalCompanies = Company::count();
        $totalCourses = Course::count();
        $pendingCourses = Course::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalCompanies', 'totalCourses', 'pendingCourses'));
    }
    
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }
    
    public function companies()
    {
        $companies = Company::with('admin')->paginate(10);
        return view('admin.companies', compact('companies'));
    }
    
    public function courses()
    {
        $courses = Course::with(['creator', 'company'])->paginate(10);
        return view('admin.courses', compact('courses'));
    }
    
    public function pendingCourses()
    {
        $courses = Course::where('status', 'pending')
            ->with(['creator', 'company'])
            ->paginate(10);
            
        return view('admin.pending-courses', compact('courses'));
    }
    
    public function createUser()
    {
        $companies = Company::all();
        return view('admin.create-user', compact('companies'));
    }
    
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,company,employee',
            'company_id' => 'nullable|exists:companies,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $request->company_id,
        ]);
        
        return redirect()->route('admin.users')->with('success', __('messages.user_created'));
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $companies = Company::all();
        return view('admin.edit-user', compact('user', 'companies'));
    }
    
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,company,employee',
            'company_id' => 'nullable|exists:companies,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->company_id = $request->company_id;
        
        if ($request->filled('password')) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('admin.users')->with('success', __('messages.user_updated'));
    }
    
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        // Don't allow deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', __('messages.cannot_delete_self'));
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', __('messages.user_deleted'));
    }
    
    public function createCompany()
    {
        return view('admin.create-company');
    }
    
    public function storeCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'theme' => 'required|string|in:orange,blue,green,purple,red',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Create company admin
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'company',
        ]);
        
        // Create company
        $company = Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'theme' => $request->theme,
            'admin_id' => $admin->id,
        ]);
        
        // Update admin with company_id
        $admin->company_id = $company->id;
        $admin->save();
        
        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company_logos', 'public');
            $company->logo = $path;
            $company->save();
        }
        
        return redirect()->route('admin.companies')->with('success', __('messages.company_created'));
    }
    
    public function editCompany($id)
    {
        $company = Company::with('admin')->findOrFail($id);
        return view('admin.edit-company', compact('company'));
    }
    
    public function updateCompany(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        
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
            $path = $request->file('logo')->store('company_logos', 'public');
            $company->logo = $path;
        }
        
        $company->save();
        
        return redirect()->route('admin.companies')->with('success', __('messages.company_updated'));
    }
    
    public function destroyCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        
        return redirect()->route('admin.companies')->with('success', __('messages.company_deleted'));
    }
}