@extends('admin.layouts.master')

@section('title', 'Pages Management - Admin Panel')

@section('content')
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Pages Management</h2>
            <p class="text-stone-600">Create and manage custom pages for your website</p>
        </div>
        <div>
            <a href="{{ route('admin.pages.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Create New Page
            </a>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Total Pages</p>
                <p class="text-2xl font-bold text-stone-800 mt-1">{{ $pages->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-alt text-red-600"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Active Pages</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $pages->where('is_active', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-red-600"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Draft Pages</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $pages->where('is_active', false)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-pencil-alt text-amber-600"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-stone-200">
            <thead>
                <tr class="bg-stone-50/50">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-stone-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-stone-500 uppercase tracking-wider">Last Updated</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-stone-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @foreach($pages as $page)
                <tr class="hover:bg-stone-50/80 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-stone-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-stone-400"></i>
                            </div>
                            <span class="font-semibold text-stone-800">{{ $page->title }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-600">
                        <code class="bg-stone-100 px-2 py-1 rounded text-stone-500 text-xs">{{ $page->slug }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $page->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-600' }}">
                            {{ $page->is_active ? 'Active' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-stone-500">
                        {{ $page->updated_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('customer.page.show', $page->slug) }}" target="_blank" 
                               class="p-2 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Preview">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $page->id) }}" 
                               class="p-2 text-stone-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline-block" 
                                  onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($pages->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-stone-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-alt text-4xl text-stone-200 mb-4"></i>
                            <p>No pages found. Start by creating a new one!</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    @if($pages->hasPages())
    <div class="px-6 py-4 border-t border-stone-200 bg-stone-50/50">
        {{ $pages->links() }}
    </div>
    @endif
</div>
@endsection
