@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">{{ __('messages.course_management') }}</h1>
        
        <a href="{{ route('admin.courses.pending') }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark">
            {{ __('messages.pending_approvals') }}
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
                            {{ __('messages.course_title') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.company') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.course_creator') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.status') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('messages.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="coursesList">
                    @foreach($courses as $course)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($course->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $course->company->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $course->creator->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    @if($course->status === 'approved')
                                        {{ __('messages.approved') }}
                                    @elseif($course->status === 'rejected')
                                        {{ __('messages.rejected') }}
                                    @else
                                        {{ __('messages.pending_approval') }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('courses.show', $course->id) }}" class="text-primary hover:underline mr-3">{{ __('messages.view') }}</a>
                                
                                @if($course->status === 'pending')
                                    <form action="{{ route('courses.approve', $course->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline mr-3">{{ __('messages.approve') }}</button>
                                    </form>
                                    <form action="{{ route('courses.reject', $course->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline">{{ __('messages.reject') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $courses->links() }}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const rows = document.querySelectorAll('#coursesList tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            rows.forEach(row => {
                const title = row.querySelector('div.text-sm.font-medium').textContent.toLowerCase();
                const description = row.querySelector('div.text-xs.text-gray-500').textContent.toLowerCase();
                const company = row.querySelectorAll('div.text-sm.text-gray-900')[0].textContent.toLowerCase();
                const creator = row.querySelectorAll('div.text-sm.text-gray-900')[1].textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm) || company.includes(searchTerm) || creator.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection