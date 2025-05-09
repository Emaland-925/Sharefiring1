@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.company_management') }}</h1>
        
        <a href="{{ route('admin.companies.create') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
            {{ __('messages.add_company') }}
        </a>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <div class="relative">
                <input type="text" id="search" class="w-full px-4 py-2 pl-10 border rounded-md" placeholder="{{ __('messages.search') }}...">
                <svg class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.company_name') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.admin') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.theme') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.date') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="companiesList">
                    @foreach($companies as $company)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($company->logo)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-primary font-medium">
                                            {{ substr($company->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $company->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $company->admin ? $company->admin->name : '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $company->admin ? $company->admin->email : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $company->theme }}-100 text-{{ $company->theme }}-800">
                                    {{ $company->theme }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $company->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.companies.edit', $company->id) }}" class="text-primary hover:underline mr-3">{{ __('messages.edit') }}</a>
                                <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">{{ __('messages.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $companies->links() }}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const rows = document.querySelectorAll('#companiesList tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
                const name = row.querySelector('div.text-sm.font-medium').textContent.toLowerCase();
                const admin = row.querySelectorAll('div.text-sm')[0]?.textContent.toLowerCase() || '';
                const email = row.querySelectorAll('div.text-sm')[1]?.textContent.toLowerCase() || '';
                
                if (name.includes(searchTerm) || admin.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection