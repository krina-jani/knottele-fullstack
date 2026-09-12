@extends('admin.layouts.master')

@section('title', 'Create Page - Admin Panel')

@section('content')
<div class="mb-8">
    <div class="flex items-center space-x-3 mb-2">
        <a href="{{ route('admin.pages.index') }}" class="text-stone-400 hover:text-stone-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-stone-800">Create New Page</h2>
    </div>
    <p class="text-stone-600 ml-7">Design and publish a new content page</p>
</div>

<form action="{{ route('admin.pages.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-stone-700 mb-2" for="title">
                        Page Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full border border-stone-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all @error('title') border-rose-500 @enderror"
                           placeholder="Enter page title (e.g., About Us)">
                    @error('title')
                        <p class="text-rose-500 text-xs mt-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-semibold text-stone-700 mb-2" for="editor">
                        Page Content <span class="text-rose-500">*</span>
                    </label>
                    <div class="prose max-w-none">
                        <textarea id="editor" name="content">{{ old('content') }}</textarea>
                    </div>
                    @error('content')
                        <p class="text-rose-500 text-xs mt-1 italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-6 flex items-center">
                    <i class="fas fa-search text-red-500 mr-2"></i> SEO Settings
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-2" for="meta_title">
                            Meta Title
                        </label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                               class="w-full border border-stone-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                               placeholder="Page title as it appears in search results">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-2" for="meta_description">
                            Meta Description
                        </label>
                        <textarea name="meta_description" id="meta_description" rows="4"
                                  class="w-full border border-stone-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                  placeholder="Brief summary of the page for search engines">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-6">Publish Status</h3>
                
                <div class="mb-8">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="is_active" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        </div>
                        <span class="ml-3 text-sm font-medium text-stone-600 group-hover:text-stone-800 transition-colors">Visible on Website</span>
                    </label>
                </div>

                <div class="flex flex-col space-y-3">
                    <button type="submit" class="btn-primary w-full py-3 justify-center shadow-lg shadow-red-100">
                        <i class="fas fa-save mr-2"></i>Create Page
                    </button>
                    <a href="{{ route('admin.pages.index') }}" 
                       class="btn-secondary w-full py-3 justify-center">
                        Cancel
                    </a>
                </div>
            </div>

            <div class="bg-red-50 p-6 rounded-2xl border border-red-100">
                <h4 class="text-red-800 font-semibold mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i> Pro Tip
                </h4>
                <p class="text-red-700 text-sm leading-relaxed">
                    Use clear, descriptive titles. They help both your users and search engines understand what the page is about.
                </p>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable {
        min-height: 400px;
        border-radius: 0 0 12px 12px !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: #d6d3d1 !important; /* stone-300 */
    }
    .ck.ck-toolbar {
        border-radius: 12px 12px 0 0 !important;
        background-color: #fafaf9 !important; /* stone-50 */
        border-color: #d6d3d1 !important;
    }
</style>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ],
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
