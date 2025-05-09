@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="text-primary hover:underline flex items-center">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('messages.back') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('messages.add_user') }}</h1>
        
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 mb-2">{{ __('messages.name') }}</label>
                <input id="name" type="text" class="w-full px-4 py-2 border rounded-md @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required>
                
                @error('name')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">{{ __('messages.email') }}</label>
                <input id="email" type="email" class="w-full px-4 py-2 border rounded-md @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required>
                
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="role" class="block text-gray-700 mb-2">{{ __('messages.role') }}</label>
                <select id="role" name="role" class="w-full px-4 py-2 border rounded-md @error('role') border-red-500 @enderror" required>
                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>{{ __('messages.role_employee') }}</option>
                    <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>{{ __('messages.role_company') }}</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('messages.role_admin') }}</option>
                </select>
                
                @error('role')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4" id="companyField">
                <label for="company_id" class="block text-gray-700 mb-2">{{ __('messages.company') }}</label>
                <select id="company_id" name="company_id" class="w-full px-4 py-2 border rounded-md @error('company_id') border-red-500 @enderror">
                    <option value="">{{ __('messages.select_option') }}</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                
                @error('company_id')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 mb-2">{{ __('messages.password') }}</label>
                <input id="password" type="password" class="w-full px-4 py-2 border rounded-md @error('password') border-red-500 @enderror" name="password" required>
                
                @error('password')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 mb-2">{{ __('messages.confirm_password') }}</label>
                <input id="password_confirmation" type="password" class="w-full px-4 py-2 border rounded-md" name="password_confirmation" required>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                    {{ __('messages.create') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const companyField = document.getElementById('companyField');
        
        function toggleCompanyField() {
            if (roleSelect.value === 'employee') {
                companyField.style.display = 'block';
            } else {
                companyField.style.display = 'none';
            }
        }
        
        toggleCompanyField();
        
        roleSelect.addEventListener('change', toggleCompanyField);
    });
</script>
@endsection