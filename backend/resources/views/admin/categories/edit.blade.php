@extends('admin.layouts.master')

@section('title', 'Edit Category')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Edit Category</h2>
                </div>
                <p class="text-gray-600 text-sm sm:text-base mt-1">Update category information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                    <i class="fas fa-list mr-2"></i>View All
                </a>
                <button onclick="showDeleteModal()" class="btn-secondary bg-rose-50 text-rose-600 hover:bg-rose-100">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-12">
        <i class="fas fa-spinner fa-spin text-indigo-500 text-3xl"></i>
        <p class="mt-4 text-gray-600">Loading category data...</p>
    </div>

    <!-- Form Content (loaded dynamically) -->
    <div id="formContent" class="hidden">
        <!-- Will be populated by JavaScript -->
    </div>

    <!-- Media Library Modal (Same as Product) -->
    <div id="media-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Select Media</h3>
                        <button onclick="closeMediaModal()" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Search & Upload -->
                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                        <div class="flex-1">
                            <input type="text" id="media-search" placeholder="Search files..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500">
                        </div>
                        <div>
                            <label for="media-upload" class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                Upload New
                            </label>
                            <input type="file" id="media-upload" class="hidden" multiple accept="image/*">
                        </div>
                    </div>

                    <!-- Media Grid -->
                    <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 h-96 overflow-y-auto p-2 border rounded bg-gray-50">
                        <!-- Media items will be injected here -->
                    </div>

                    <!-- Pagination -->
                    <div id="media-pagination" class="mt-4 flex justify-between items-center">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="confirmMediaSelection()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Select
                    </button>
                    <button type="button" onclick="closeMediaModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-gray-600 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-6 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-rose-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold text-gray-900">Delete Category</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" id="deleteMessage">Are you sure you want to delete this category? This action cannot be undone.</p>
                                    <p class="mt-2 text-xs text-gray-500">Subcategories will become main categories.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="confirmDelete()" class="btn-primary bg-rose-600 hover:bg-rose-700 ml-3">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                        <button type="button" onclick="closeDeleteModal()" class="btn-secondary mt-3 sm:mt-0">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Axios instance
    const axiosInstance = axios.create({
        baseURL: '{{ url('') }}/api/admin/',
        headers: {
            'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
        }
    });

    // Global variables
    let selectedMediaId = null;
    let selectedMediaUrl = null;
    let categoryData = null;
    let allAttributes = [];
    let allSpecGroups = [];
    let parentCategories = [];

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Category edit page loaded for ID:', {{ $id }});
        loadCategoryData();
    });

    // Load category data
    async function loadCategoryData() {
        try {
            const response = await axiosInstance.get(`/categories/{{ $id }}`);

            if (response.data.success) {
                categoryData = response.data.data;
                console.log('Category data loaded:', categoryData);

                // Load other data in parallel
                await Promise.all([
                    loadParentCategories(),
                    loadAttributes(),
                    loadSpecificationGroups()
                ]);

                // Render form
                renderForm();

                // Hide loading, show form
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('formContent').classList.remove('hidden');

                toastr.success('Category data loaded successfully');
            } else {
                throw new Error(response.data.message || 'Failed to load category');
            }
        } catch (error) {
            console.error('Error loading category:', error);
            document.getElementById('loadingState').innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-exclamation-triangle text-rose-500 text-3xl"></i>
                    <p class="mt-4 text-gray-600">Failed to load category data</p>
                    <p class="text-sm text-rose-500 mt-2">${error.message || 'Please try again'}</p>
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary mt-4">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Categories
                    </a>
                </div>
            `;
        }
    }

    // Load parent categories
    async function loadParentCategories() {
        try {
            const response = await axiosInstance.get('/categories/dropdown', {
                params: {
                    exclude_id: {{ $id }}
                }
            });

            if (response.data.success) {
                parentCategories = response.data.data || [];
            }
        } catch (error) {
            console.error('Error loading parent categories:', error);
            parentCategories = [];
        }
    }

    // Load attributes
    async function loadAttributes() {
        try {
            const response = await axiosInstance.get('/attributes/dropdown');

            if (response.data.success) {
                allAttributes = response.data.data || [];
                console.log('Attributes loaded:', allAttributes.length);
            }
        } catch (error) {
            console.error('Error loading attributes:', error);
            allAttributes = [];
        }
    }

    // Load specification groups
    async function loadSpecificationGroups() {
        try {
            const response = await axiosInstance.get('/specification-groups/dropdown');

            if (response.data.success) {
                allSpecGroups = response.data.data || [];
                console.log('Spec groups loaded:', allSpecGroups.length);
            }
        } catch (error) {
            console.error('Error loading specification groups:', error);
            allSpecGroups = [];
        }
    }

    // Render form
    function renderForm() {
        const formContent = document.getElementById('formContent');

        formContent.innerHTML = `
            <form id="categoryForm" class="space-y-8" enctype="multipart/form-data">
                <input type="hidden" id="categoryId" value="{{ $id }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Basic Information Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Category Name & Slug -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Category Name <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" required
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            placeholder="e.g., Men's Clothing"
                                            value="${categoryData.name || ''}">
                                        <div id="nameError" class="hidden mt-2 text-sm text-rose-600"></div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Slug <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" id="slug" name="slug" required
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            placeholder="e.g., mens-clothing"
                                            value="${categoryData.slug || ''}">
                                        <div id="slugError" class="hidden mt-2 text-sm text-rose-600"></div>
                                    </div>
                                </div>

                                <!-- Parent Category -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                                    <select id="parent_id" name="parent_id"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="">No Parent (Main Category)</option>
                                        ${generateParentOptions(parentCategories, categoryData.parent_id)}
                                    </select>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea id="description" name="description" rows="4"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Describe this category...">${categoryData.description || ''}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">SEO Settings</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Meta Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                    <input type="text" id="meta_title" name="meta_title"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Title for search engines"
                                        value="${categoryData.meta_title || ''}">
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                    <textarea id="meta_description" name="meta_description" rows="3"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="Description for search engines">${categoryData.meta_description || ''}</textarea>
                                </div>

                                <!-- Meta Keywords -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                    <input type="text" id="meta_keywords" name="meta_keywords"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        placeholder="keyword1, keyword2, keyword3"
                                        value="${categoryData.meta_keywords || ''}">
                                </div>
                            </div>
                        </div>

                        <!-- Specification Groups Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Specification Groups</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 mb-4">Select specification groups to assign to this category</p>

                                <div class="mb-4">
                                    <div class="relative">
                                        <input type="text" id="specGroupSearch" placeholder="Search specification groups..."
                                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full"
                                            onkeyup="filterSpecGroups()">
                                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                </div>

                                <div class="space-y-3 max-h-96 overflow-y-auto p-4 border rounded-lg bg-gray-50" id="specificationGroupsContainer">
                                    ${renderSpecificationGroups()}
                                </div>
                            </div>
                        </div>

                        <!-- Attributes Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Attributes</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 mb-4">Select attributes for variant creation (size, color, etc.)</p>

                                <div class="mb-4">
                                    <div class="relative">
                                        <input type="text" id="attributeSearch" placeholder="Search attributes..."
                                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full"
                                            onkeyup="filterAttributes()">
                                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <input type="checkbox" id="selectAllAttributes" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                           onchange="toggleAllAttributes(this.checked)">
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Attribute
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Required
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Filterable
                                                </th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Sort Order
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="attributesContainer">
                                            ${renderAttributes()}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Image Upload Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Category Image</h3>
                            </div>
                            <div class="p-6">
                                <div class="text-center">
                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mb-4">
                                        ${categoryData.image ?
                                            `<div class="w-full h-64 rounded-lg overflow-hidden border">
                                                <img src="${categoryData.image}" alt="${categoryData.name}" class="w-full h-full object-cover">
                                            </div>` :
                                            `<div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                                <div class="text-center">
                                                    <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                                                    <p class="text-sm text-gray-500">No image selected</p>
                                                </div>
                                            </div>`
                                        }
                                    </div>

                                    <input type="hidden" id="image_id" name="image_id" value="${categoryData.image_id || ''}">

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col space-y-2">
                                        <button type="button" onclick="openMediaLibrary()" class="btn-secondary">
                                            <i class="fas fa-images mr-2"></i>Select from Media Library
                                        </button>
                                        <button type="button" onclick="clearImage()" class="btn-secondary bg-gray-100 text-gray-700 hover:bg-gray-200">
                                            <i class="fas fa-times mr-2"></i>Remove Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Settings</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <!-- Sort Order -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                    <input type="number" id="sort_order" name="sort_order" value="${categoryData.sort_order || 0}" min="0"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>

                                <!-- Status Toggle -->
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Status</p>
                                        <p class="text-xs text-gray-500">Category visibility</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" id="status" name="status" ${categoryData.status ? 'checked' : ''}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- Featured Toggle -->
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Featured</p>
                                        <p class="text-xs text-gray-500">Show in featured sections</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" id="featured" name="featured" ${categoryData.featured ? 'checked' : ''}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                                <!-- Show in Navigation -->
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Show in Navigation</p>
                                        <p class="text-xs text-gray-500">Display in main navigation</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" id="show_in_nav" name="show_in_nav" ${categoryData.show_in_nav ? 'checked' : ''}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Save Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Save Changes</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="text-sm text-gray-600">
                                        <p class="mb-2"><i class="fas fa-info-circle text-indigo-500 mr-2"></i>Review all information before saving</p>
                                    </div>

                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.categories.index') }}" class="btn-secondary flex-1 text-center">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn-primary flex-1">
                                            <i class="fas fa-save mr-2"></i>Update Category
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        `;

        // Setup event listeners
        setupEventListeners();
    }

    // Generate parent category options
    function generateParentOptions(categories, selectedParentId = null, level = 0) {
        let options = '';
        const prefix = '— '.repeat(level);

        categories.forEach(category => {
            const selected = selectedParentId == category.id ? 'selected' : '';
            options += `<option value="${category.id}" ${selected}>${prefix}${category.name}</option>`;

            // Add children recursively
            if (category.children && category.children.length > 0) {
                options += generateParentOptions(category.children, selectedParentId, level + 1);
            }
        });

        return options;
    }

    // Render specification groups
    function renderSpecificationGroups() {
        if (allSpecGroups.length === 0) {
            return `
                <div class="text-center py-8 text-gray-500">
                    No specification groups found. Create groups first.
                </div>
            `;
        }

        const selectedGroups = categoryData.spec_group_ids || [];

        return allSpecGroups.map(group => `
            <div class="spec-group-item flex items-center p-3 bg-white rounded-lg border hover:border-indigo-300 transition-colors">
                <input type="checkbox"
                       id="spec_group_${group.id}"
                       name="spec_group_ids[]"
                       value="${group.id}"
                       class="spec-group-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3"
                       ${selectedGroups.includes(group.id) ? 'checked' : ''}>
                <label for="spec_group_${group.id}" class="flex-1 cursor-pointer">
                    <div class="font-medium text-gray-900">${group.name}</div>
                </label>
            </div>
        `).join('');
    }

    // Render attributes
    function renderAttributes() {
        if (allAttributes.length === 0) {
            return `
                <tr id="attributesLoading">
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        No attributes found. Create attributes first.
                    </td>
                </tr>
            `;
        }

        const categoryAttributes = categoryData.attributes || [];

        return allAttributes.map(attribute => {
            const categoryAttr = categoryAttributes.find(ca => ca.id == attribute.id);
            const isSelected = !!categoryAttr;
            const isRequired = categoryAttr ? categoryAttr.pivot.is_required : false;
            const isFilterable = categoryAttr ? categoryAttr.pivot.is_filterable : false;
            const sortOrder = categoryAttr ? categoryAttr.pivot.sort_order : 0;

            return `
                <tr class="attribute-item">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <input type="checkbox"
                               id="attribute_${attribute.id}"
                               data-id="${attribute.id}"
                               class="attribute-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               ${isSelected ? 'checked' : ''}
                               onchange="toggleAttributeOptions('${attribute.id}', this.checked)">
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">${attribute.name}</div>
                            <div class="ml-2 text-xs text-gray-500">(${attribute.code})</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <input type="checkbox"
                               id="attribute_${attribute.id}_required"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 attribute-required"
                               ${isSelected ? '' : 'disabled'}
                               ${isRequired ? 'checked' : ''}>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <input type="checkbox"
                               id="attribute_${attribute.id}_filterable"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 attribute-filterable"
                               ${isSelected ? '' : 'disabled'}
                               ${isFilterable ? 'checked' : ''}>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <input type="number"
                               id="attribute_${attribute.id}_order"
                               value="${sortOrder}"
                               min="0"
                               class="attribute-sort-order w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                               ${isSelected ? '' : 'disabled'}>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Setup event listeners
    function setupEventListeners() {
        const form = document.getElementById('categoryForm');
        if (form) {
            form.addEventListener('submit', updateCategory);
        }

        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('blur', generateSlug);
        }

        // Clear errors on input
        ['name', 'slug'].forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element) {
                element.addEventListener('input', function() {
                    const errorElement = document.getElementById(fieldId + 'Error');
                    if (errorElement) {
                        errorElement.classList.add('hidden');
                        errorElement.textContent = '';
                    }
                });
            }
        });
    }

    // Generate slug from name
    function generateSlug() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (nameInput && slugInput && nameInput.value && (!slugInput.value || slugInput.value === '')) {
            const slug = nameInput.value.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    }

    // Toggle all attributes
    function toggleAllAttributes(isChecked) {
        document.querySelectorAll('.attribute-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
            toggleAttributeOptions(checkbox.dataset.id, isChecked);
        });
    }

    // Toggle attribute options
    function toggleAttributeOptions(attributeId, isChecked) {
        const requiredCheckbox = document.getElementById(`attribute_${attributeId}_required`);
        const filterableCheckbox = document.getElementById(`attribute_${attributeId}_filterable`);
        const sortOrderInput = document.getElementById(`attribute_${attributeId}_order`);

        if (requiredCheckbox) requiredCheckbox.disabled = !isChecked;
        if (filterableCheckbox) filterableCheckbox.disabled = !isChecked;
        if (sortOrderInput) sortOrderInput.disabled = !isChecked;

        if (!isChecked) {
            if (requiredCheckbox) requiredCheckbox.checked = false;
            if (filterableCheckbox) filterableCheckbox.checked = false;
            if (sortOrderInput) sortOrderInput.value = '0';
        }
    }

    // Filter attributes
    function filterAttributes() {
        const searchTerm = document.getElementById('attributeSearch').value.toLowerCase();
        document.querySelectorAll('.attribute-item').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    // Filter specification groups
    function filterSpecGroups() {
        const searchTerm = document.getElementById('specGroupSearch').value.toLowerCase();
        document.querySelectorAll('.spec-group-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    // =============== MEDIA MANAGEMENT FUNCTIONS ===============

    let currentMode = 'main'; // Only 'main' for category image
    let selectedImages = [];
    let currentMediaData = null;

    function openMediaLibrary() {
        // Function renamed to match button call, but internally uses product modal logic name
        openMediaModal('main');
    }

    function openMediaModal(mode = 'main') {
        currentMode = mode;
        selectedImages = [];

        let modalTitle = 'Select Category Image';
        document.getElementById('modal-title').textContent = modalTitle;

        document.getElementById('media-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        loadMedia(1);
    }

    function closeMediaModal() {
        document.getElementById('media-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        selectedImages = [];
    }

    async function loadMedia(page = 1, search = '') {
        const grid = document.getElementById('media-grid');
        const pagination = document.getElementById('media-pagination');

        grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">Loading media...</div>';

        try {
            // Use the same route as product create
            const response = await axiosInstance.get('/media', {
                params: { page, search, type: 'image' }
            });

            // Standardize response structure handling
            let mediaData = response.data;
            // If wrapped in success/data
            if (mediaData.success && mediaData.data) {
                mediaData = mediaData.data;
            }
            
            currentMediaData = mediaData;
            renderMediaGrid(mediaData.data || mediaData); // Handle if paginated or direct array
            renderPagination(mediaData);
        } catch (error) {
            console.error('Media load error:', error);
            grid.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Error loading media.</div>';
            toastr.error('Failed to load media');
        }
    }

    function renderMediaGrid(media) {
        const grid = document.getElementById('media-grid');
        
        if (!media || media.length === 0) {
            grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">No media found.</div>';
            return;
        }

        let html = '';
        media.forEach(item => {
            const isSelected = selectedImages.some(img => img.id === item.id);
            const url = item.thumbnail_url || item.url || item.full_url || item.path;
            const name = item.file_name || item.name || item.filename;

            html += `
            <div class="relative border rounded-lg overflow-hidden cursor-pointer group ${isSelected ? 'ring-2 ring-red-500' : ''}" 
                 onclick="toggleImageSelection(${item.id}, '${url}')" data-media='${JSON.stringify(item)}'>
                <img src="${url}" class="w-full h-32 object-cover">
                <div class="p-2 text-xs truncate">${name}</div>
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition"></div>
                ${isSelected ? 
                    '<div class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">✓</div>' 
                    : ''}
            </div>
            `;
        });

        grid.innerHTML = html;
    }

    function renderPagination(data) {
        const pagination = document.getElementById('media-pagination');
        if (!data || !data.links || data.links.length <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = '<div class="flex space-x-2">';
        
        data.links.forEach(link => {
            if (link.url) {
                const active = link.active ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700';
                // Extract page number from URL
                let page = 1;
                try {
                    const urlObj = new URL(link.url);
                    page = urlObj.searchParams.get('page');
                } catch(e) {
                     // fallback for relative urls if needed
                }
                
                html += `
                <button type="button" onclick="loadMedia(${page}, document.getElementById('media-search').value)" 
                        class="px-3 py-1 rounded ${active} hover:bg-blue-600 hover:text-white transition">
                    ${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}
                </button>
                `;
            }
        });
        
        html += '</div>';
        pagination.innerHTML = html;
    }

    function toggleImageSelection(id, url) {
        // For category image, we only need single selection
        selectedImages = [{ id, url }];
        
        // Re-render grid to show selection
        const mediaData = currentMediaData.data || currentMediaData;
        renderMediaGrid(mediaData);
    }

    function confirmMediaSelection() {
        if (selectedImages.length > 0) {
            const image = selectedImages[0];
            document.getElementById('image_id').value = image.id;
            
            // Update Preview
            const preview = document.getElementById('imagePreview');
             preview.innerHTML = `
                <div class="w-full h-64 rounded-lg overflow-hidden border">
                    <img src="${image.url}" class="w-full h-full object-cover">
                </div>
            `;
            
            closeMediaModal();
        } else {
            toastr.warning('Please select an image');
        }
    }
    
    // Debounced search
    document.getElementById('media-search').addEventListener('input', function(e) {
         clearTimeout(window.searchTimeout);
         window.searchTimeout = setTimeout(() => {
             loadMedia(1, e.target.value);
         }, 500);
    });
    
    // Handle file upload
    document.getElementById('media-upload').addEventListener('change', async function(e) {
        const files = e.target.files;
        if (!files.length) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        try {
            // Using route consistent with product page
            await axiosInstance.post('/media/upload', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            toastr.success('Files uploaded successfully');
            loadMedia(1); 
            e.target.value = ''; 
        } catch (error) {
            console.error('Upload error:', error);
            toastr.error('Failed to upload files');
        }
    });

    // Clear image function
    function clearImage() {
        document.getElementById('image_id').value = '';
        document.getElementById('imagePreview').innerHTML = `
            <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                    <p class="text-sm text-gray-500">No image selected</p>
                </div>
            </div>
        `;
        selectedMediaId = null;
        selectedMediaUrl = null;
        toastr.info('Image removed');
    }

    // Update category
    async function updateCategory(e) {
        e.preventDefault();

        // Collect form data
        const formData = {
            name: document.getElementById('name').value,
            slug: document.getElementById('slug').value,
            description: document.getElementById('description').value,
            parent_id: document.getElementById('parent_id').value || null,
            image_id: document.getElementById('image_id').value || null,
            meta_title: document.getElementById('meta_title').value,
            meta_description: document.getElementById('meta_description').value,
            meta_keywords: document.getElementById('meta_keywords').value,
            sort_order: parseInt(document.getElementById('sort_order').value) || 0,
            status: document.getElementById('status').checked ? 1 : 0,
            featured: document.getElementById('featured').checked ? 1 : 0,
            show_in_nav: document.getElementById('show_in_nav').checked ? 1 : 0,
            spec_group_ids: [],
            attributes: {}
        };

        // Collect specification groups
        document.querySelectorAll('.spec-group-checkbox:checked').forEach(checkbox => {
            formData.spec_group_ids.push(parseInt(checkbox.value));
        });

        // Collect attributes
        document.querySelectorAll('.attribute-checkbox:checked').forEach(checkbox => {
            const attributeId = checkbox.dataset.id;
            formData.attributes[attributeId] = {
                is_required: document.getElementById(`attribute_${attributeId}_required`).checked ? 1 : 0,
                is_filterable: document.getElementById(`attribute_${attributeId}_filterable`).checked ? 1 : 0,
                sort_order: parseInt(document.getElementById(`attribute_${attributeId}_order`).value) || 0
            };
        });

        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        submitBtn.disabled = true;

        // Clear previous errors
        ['nameError', 'slugError'].forEach(errorId => {
            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
        });

        try {
            const response = await axiosInstance.put(`/categories/{{ $id }}`, formData);

            if (response.data.success) {
                toastr.success(response.data.message);

                // Reload category data after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        } catch (error) {
            if (error.response && error.response.status === 422) {
                // Validation errors
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(field => {
                    const errorElement = document.getElementById(field + 'Error');
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                        errorElement.classList.remove('hidden');
                    } else {
                        toastr.error(errors[field][0]);
                    }
                });
                toastr.error('Please fix the validation errors');
            } else {
                toastr.error(error.response?.data?.message || 'Failed to update category');
            }
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Show delete modal
    function showDeleteModal() {
        document.getElementById('deleteMessage').textContent =
            `Are you sure you want to delete "${categoryData.name}"? This action cannot be undone.`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // Close delete modal
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Confirm delete
    async function confirmDelete() {
        try {
            const response = await axiosInstance.delete(`/categories/{{ $id }}`);

            if (response.data.success) {
                toastr.success(response.data.message);
                closeDeleteModal();

                // Redirect to categories list after 1 second
                setTimeout(() => {
                    window.location.href = '{{ route("admin.categories.index") }}';
                }, 1000);
            }
        } catch (error) {
            toastr.error(error.response?.data?.message || 'Failed to delete category');
            closeDeleteModal();
        }
    }
</script>

<style>
    /* Switch styling */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #6366f1;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #6366f1;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Media library modal */
    .selected-media .border-2 {
        border-color: #6366f1 !important;
    }

    /* Loading animation */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Table styling */
    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    th {
        position: sticky;
        top: 0;
        background-color: #f9fafb;
        z-index: 10;
    }

    /* Form focus styles */
    input:focus, textarea:focus, select:focus {
        outline: none;
        ring: 2px;
        ring-color: #6366f1;
    }

    /* Scrollbar styling */
    .overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }

    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush
