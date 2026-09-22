@extends('admin.layouts.master')

@section('title', 'Category Management')

@section('content')
    <div class="mb-8 no-print">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-stone-800 mb-2">Category Management</h2>
                <p class="text-stone-600 text-sm sm:text-base">Organize your products with categories and subcategories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn-primary btn-sm whitespace-nowrap shadow-sm">
                <i class="fas fa-plus mr-1 text-xs"></i>Add Category
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 no-print">
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600 mr-4">
                    <i class="fas fa-folder text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-stone-600">Total Categories</p>
                    <h3 class="text-2xl font-bold text-stone-800" id="totalCategories">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600 mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-stone-600">Active Categories</p>
                    <h3 class="text-2xl font-bold text-stone-800" id="activeCategories">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600 mr-4">
                    <i class="fas fa-sitemap text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-stone-600">Main Categories</p>
                    <h3 class="text-2xl font-bold text-stone-800" id="mainCategories">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-amber-100 text-amber-600 mr-4">
                    <i class="fas fa-crown text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-stone-600">Top Category</p>
                    <h3 class="text-lg font-semibold text-stone-800 truncate" id="popularCategory">-</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table - Tabulator -->
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-200 bg-stone-50/50">
            <h3 class="text-lg font-semibold text-stone-800">All Categories</h3>
        </div>
        <div class="p-3 sm:p-6">
            <!-- Tabulator Toolbar -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <div class="order-2 sm:order-1 w-full sm:w-auto">
                    <div class="relative w-full sm:w-[260px]">
                        <input type="text" id="searchInput" placeholder="Search categories..."
                            class="pl-10 pr-4 py-2 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent w-full text-stone-900 placeholder-stone-400 text-xs sm:text-sm shadow-2xs">
                        <i class="fas fa-search absolute left-3.5 top-3 text-stone-400 text-xs sm:text-sm"></i>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 order-1 sm:order-2 w-full sm:w-auto">
                    <!-- Refresh Button (Small) -->
                    <button onclick="refreshAll()" class="btn-secondary btn-sm" title="Refresh">
                        <i class="fas fa-sync-alt text-xs"></i>
                        <span>Refresh</span>
                    </button>
                    <!-- Bulk Actions Button (Small) -->
                    <button id="bulkActionsBtn" class="btn-secondary btn-sm" title="Bulk Actions">
                        <i class="fas fa-bolt text-xs"></i>
                        <span>Bulk Actions</span>
                    </button>
                    <!-- Column Visibility Button (Small) -->
                    <button id="columnVisibilityBtn" class="btn-secondary btn-sm" title="Columns">
                        <i class="fas fa-columns text-xs"></i>
                        <span>Columns</span>
                    </button>
                    <!-- Export Options: Excel, PDF, Print -->
                    <button type="button" onclick="exportCategories('excel')" class="btn-secondary btn-sm hover:text-emerald-700 hover:bg-emerald-50 text-stone-700 transition-colors shadow-2xs" title="Export as Excel Sheet (.xlsx)">
                        <i class="fas fa-file-excel text-emerald-600 text-xs"></i>
                        <span>Excel</span>
                    </button>
                    <button type="button" onclick="exportCategories('pdf')" class="btn-secondary btn-sm hover:text-red-700 hover:bg-red-50 text-stone-700 transition-colors shadow-2xs" title="Export as PDF Document (.pdf)">
                        <i class="fas fa-file-pdf text-red-500 text-xs"></i>
                        <span>PDF</span>
                    </button>
                    <button type="button" onclick="exportCategories('print')" class="btn-secondary btn-sm hover:text-stone-900 hover:bg-stone-100 text-stone-700 transition-colors shadow-2xs" title="Print Clean Table Report">
                        <i class="fas fa-print text-stone-600 text-xs"></i>
                        <span>Print</span>
                    </button>
                    <!-- Add Category Button -->
                    <a href="{{ route('admin.categories.create') }}" class="btn-primary btn-sm w-full sm:w-auto shadow-sm">
                        <i class="fas fa-plus mr-1 text-xs"></i>
                        <span>+ Add Category</span>
                    </a>
                </div>
            </div>

            <!-- Tabulator Table -->
            <div id="categoriesTable"></div>
        </div>
    </div>

    <!-- Create/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-stone-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-6 border border-red-100 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-2xl bg-white"
            style="max-height: 90vh; overflow-y: auto;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-stone-800" id="modalTitle">Add New Category</h3>
                <button onclick="closeCategoryModal()" class="text-stone-400 hover:text-stone-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="categoryForm" novalidate>
                <input type="hidden" id="categoryId" name="id">

                <div class="space-y-6">
                    <!-- Category Image Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                        <div class="flex items-center space-x-6">
                            <div id="imagePreview"
                                class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <input type="hidden" id="image_id" name="image_id">
                                <button type="button" onclick="openMediaLibrary()" class="btn-secondary mb-2">
                                    <i class="fas fa-images mr-2"></i>Select from Media Library
                                </button>
                                <p class="text-xs text-gray-500">Select an image from your media library</p>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-stone-700 mb-1">Category Name
                                *</label>
                            <input type="text" id="name" name="name"
                                class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-red-500 focus:border-red-500 text-stone-900">
                            <div id="nameError" class="hidden mt-1 text-sm text-rose-600"></div>
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-stone-700 mb-1">Slug *</label>
                            <input type="text" id="slug" name="slug"
                                class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-red-500 focus:border-red-500 text-stone-900">
                            <div id="slugError" class="hidden mt-1 text-sm text-rose-600"></div>
                        </div>
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-stone-700 mb-1">Parent
                            Category</label>
                        <select id="parent_id" name="parent_id"
                            class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-red-500 focus:border-red-500 text-stone-900">
                            <option value="">No Parent (Main Category)</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-stone-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-red-500 focus:border-red-500 text-stone-900"></textarea>
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">SEO Settings</h4>

                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                    Title</label>
                                <input type="text" id="meta_title" name="meta_title"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                    Description</label>
                                <textarea id="meta_description" name="meta_description" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                    Keywords</label>
                                <input type="text" id="meta_keywords" name="meta_keywords"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-1">Canonical
                                    URL</label>
                                <input type="url" id="canonical_url" name="canonical_url"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-stone-700 mb-1">Sort
                                Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="0" min="0"
                                class="w-full border border-stone-300 rounded-lg px-4 py-2 focus:ring-red-500 focus:border-red-500 text-stone-900">
                        </div>

                        <div>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="status" name="status" checked
                                    class="rounded border-stone-300 text-red-600 focus:ring-red-500">
                                <span class="text-sm text-stone-700">Active Category</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                    <button type="button" onclick="closeCategoryModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        <span id="submitText">Save Category</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Library Modal -->
    <div id="mediaLibraryModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60] hidden">
        <div class="relative top-10 mx-auto p-6 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-2xl bg-white"
            style="max-height: 90vh;">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Select Image from Media Library</h3>
                <button onclick="closeMediaLibrary()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-4">
                <div class="relative" style="width: 300px;">
                    <input type="text" id="mediaSearchInput" placeholder="Search media..."
                        class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <div id="mediaGrid"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 overflow-y-auto"
                style="max-height: 60vh;">
                <!-- Media items will be loaded here -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
                    <p class="text-sm text-gray-500 mt-2">Loading media...</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <button type="button" onclick="closeMediaLibrary()" class="btn-secondary">Cancel</button>
                <button type="button" onclick="selectMedia()" class="btn-primary">
                    <i class="fas fa-check mr-2"></i>Select Image
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-6 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Bulk Actions</h3>
                <button onclick="closeBulkActions()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="selectedCountInfo" class="mb-4 text-sm text-gray-600"></div>

            <div class="space-y-3">
                <button onclick="applyBulkAction('activate')" class="w-full btn-secondary text-left">
                    <i class="fas fa-toggle-on mr-2 text-red-600"></i>Activate Selected
                </button>
                <button onclick="applyBulkAction('deactivate')" class="w-full btn-secondary text-left">
                    <i class="fas fa-toggle-off mr-2 text-rose-600"></i>Deactivate Selected
                </button>
                <button onclick="applyBulkAction('delete')"
                    class="w-full btn-secondary text-left border-rose-200 text-rose-600 hover:bg-rose-50">
                    <i class="fas fa-trash mr-2"></i>Delete Selected
                </button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Toggle Switch Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
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

        input:checked+.slider {
            background-color: #0ea5e9;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* Tabulator custom styles */
        .tabulator {
            border: none !important;
            background-color: transparent !important;
            font-family: inherit !important;
        }

        .tabulator .tabulator-header {
            border-bottom: 1px solid #e0f2fe !important;
            background-color: #fafaf9 !important;
        }

        .tabulator .tabulator-header .tabulator-col {
            border-right: none !important;
            background-color: transparent !important;
            font-weight: 600 !important;
            color: #1c1917 !important;
            padding: 12px 4px !important;
        }

        .tabulator .tabulator-tableholder .tabulator-table {
            background-color: white !important;
            color: #44403c !important;
        }

        .tabulator-row {
            border-bottom: 1px solid #f5f5f4 !important;
            min-height: 60px !important;
            display: flex !important;
            align-items: center !important;
        }

        .tabulator-row:hover {
            background-color: #f0f9ff !important;
        }

        .tabulator-row.tabulator-selected {
            background-color: #e0f2fe !important;
        }

        .selected-media .border-2 {
            border-color: #0ea5e9 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Fix for indexOf error if needed
        if (typeof String.prototype.startsWith !== 'function') {
            String.prototype.startsWith = function(prefix) {
                return this.indexOf(prefix) === 0;
            };
        }

        // Axios instance with interceptors
        const axiosInstance = axios.create({
            baseURL: '{{ url('') }}/api/admin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
            }
        });

        // Add request interceptor for token refresh if needed
        axiosInstance.interceptors.request.use(
            config => {
                // Add CSRF token for non-GET requests
                if (config.method !== 'get') {
                    config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                }
                return config;
            },
            error => Promise.reject(error)
        );

        // Add response interceptor for error handling
        axiosInstance.interceptors.response.use(
            response => response,
            error => {
                if (error.response?.status === 401) {
                    // Token expired, redirect to login
                    window.location.href = '{{ route('admin.signin') }}';
                } else if (error.response?.status === 500) {
                    console.error('Server error:', error.response.data);
                    toastr.error('Server error. Please try again later.');
                } else if (error.response?.status === 404) {
                    toastr.error('Resource not found');
                } else if (error.response?.status === 422) {
                    // Validation errors will be handled by individual functions
                    return Promise.reject(error);
                } else if (!error.response) {
                    // Network error
                    toastr.error('Network error. Please check your connection.');
                }
                return Promise.reject(error);
            }
        );

        // Global variables
        let categoriesTable = null;
        let isEditing = false;
        let currentPage = 1;
        let perPage = 10;
        let selectedMediaId = null;
        let selectedMediaUrl = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Categories module initialized');

            // Load data
            loadCategoriesData();
            loadStatistics();
            loadParentCategories();

            // Setup event listeners
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (nameInput && slugInput) {
                nameInput.addEventListener('blur', function() {
                    if (!isEditing && this.value && !slugInput.value) {
                        const slug = this.value.toLowerCase()
                            .replace(/[^a-z0-9 -]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        slugInput.value = slug;
                    }
                });
            }

            // Form submission
            const categoryForm = document.getElementById('categoryForm');
            if (categoryForm) {
                categoryForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveCategory();
                });
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

        // Initialize Tabulator table
        function initializeCategoriesTable(data = []) {
            categoriesTable = new Tabulator("#categoriesTable", {
                data: data,
                layout: "fitColumns",
                height: "100%",
                responsiveLayout: "hide",
                pagination: true,
                paginationSize: perPage,
                paginationSizeSelector: [10, 25, 50, 100],
                paginationCounter: "rows",
                ajaxURL: "{{ url('') }}/api/admin/categories",
                ajaxParams: {
                    sort: 'sort_order',
                    direction: 'asc'
                },
                ajaxResponse: function(url, params, response) {
                    console.log('Ajax response:', response);
                    if (response.success) {
                        // Ensure we're returning the correct data structure
                        if (response.data && response.data.data) {
                            updatePaginationInfo(response.data.meta);
                            return response.data.data;
                        } else if (Array.isArray(response.data)) {
                            return response.data;
                        }
                        return [];
                    }
                    return [];
                },
                ajaxError: function(xhr, textStatus, errorThrown) {
                    console.error('Ajax error:', xhr, textStatus, errorThrown);
                    toastr.error('Failed to load categories data');
                },
                columns: [{
                        title: "<input type='checkbox' id='selectAllCategories'>",
                        field: "id",
                        formatter: "rowSelection",
                        titleFormatter: "rowSelection",
                        hozAlign: "center",
                        headerSort: false,
                        width: 45,
                        cssClass: "select-checkbox",
                        responsive: 2
                    },
                    {
                        title: "ID",
                        field: "id",
                        width: 60,
                        sorter: "number",
                        hozAlign: "center",
                        headerFilter: "input",
                        headerFilterPlaceholder: "ID...",
                        responsive: 0
                    },
                    {
                        title: "Category",
                        field: "name",
                        widthGrow: 3,
                        minWidth: 160,
                        sorter: "string",
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search Category…",
                        responsive: 0,
                        formatter: function(cell, formatterParams, onRendered) {
                            const row = cell.getRow();
                            const data = row.getData();
                            const isSubcategory = data.parent_id !== null && data.parent_id !== 0;
                            const isActive = data.status === true || data.status === 'true' || data.status === 1;

                            return `
                    <div class="flex items-center space-x-2.5 sm:space-x-3 py-1">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden shrink-0 border border-stone-200">
                            ${data.image_url || data.image ?
                                `<img src="${data.image_url || data.image}" alt="${data.name}" class="w-full h-full object-cover">` :
                                `<i class="fas fa-folder text-gray-400"></i>`
                            }
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <p class="font-bold text-xs sm:text-sm text-stone-900 truncate">${data.name}</p>
                                ${isSubcategory ? '<span class="px-1.5 py-0.2 bg-blue-100 text-blue-800 text-[10px] rounded-full font-medium">Sub</span>' : ''}
                                <span class="inline-flex sm:hidden items-center px-1.5 py-0.2 rounded-full text-[10px] font-semibold ${isActive ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-600'}">
                                    ${isActive ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                            <p class="text-[11px] sm:text-xs text-stone-500 truncate">/${data.slug}</p>
                            <div class="flex items-center gap-2 text-[10px] text-stone-400">
                                ${data.parent_name ? `<span class="truncate">Parent: ${data.parent_name}</span>` : ''}
                                <span class="sm:hidden font-medium text-stone-600">${data.products_count || 0} products</span>
                            </div>
                        </div>
                    </div>
                `;
                        }
                    },
                    {
                        title: "Products",
                        field: "products_count",
                        width: 100,
                        sorter: "number",
                        hozAlign: "center",
                        formatter: function(cell) {
                            const count = cell.getValue() || 0;
                            return `<div class="text-center">
                            <span class="font-semibold text-gray-900">${count}</span>
                            <div class="text-xs text-gray-500">products</div>
                        </div>`;
                        },
                        responsive: 1
                    },
                    {
                        title: "Status",
                        field: "status",
                        width: 100,
                        hozAlign: "center",
                        headerFilter: "list",
                        headerFilterParams: {
                            values: {
                                "": "All",
                                "true": "Active",
                                "false": "Inactive",
                            }
                        },
                        formatter: function(cell) {
                            const row = cell.getRow();
                            const data = row.getData();
                            const isActive = data.status === true || data.status === 'true' || data
                                .status === 1;
                            return `
                    <label class="switch">
                        <input type="checkbox" class="toggle-category-status"
                               data-id="${data.id}" ${isActive ? 'checked' : ''}>
                        <span class="slider round"></span>
                    </label>
                `;
                        },
                        responsive: 1
                    },
                    {
                        title: "Sort Order",
                        field: "sort_order",
                        width: 90,
                        sorter: "number",
                        hozAlign: "center",
                        responsive: 2,
                        formatter: function(cell) {
                            const order = cell.getValue() || 0;
                            return `<span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">${order}</span>`;
                        }
                    },
                    {
                        title: "Created",
                        field: "created_at_formatted",
                        width: 130,
                        sorter: "date",
                        hozAlign: "center",
                        formatter: function(cell) {
                            const dateStr = cell.getValue();
                            return dateStr || '';
                        },
                        responsive: 2
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 110,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const data = cell.getRow().getData();
                            const id = data.id;
                            return `
                                <div class="flex space-x-1.5 justify-center">
                                    <button onclick="editCategory(${id})"
                                            class="w-7 h-7 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                            title="Edit Category">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="viewCategory(${id})"
                                            class="w-7 h-7 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors"
                                            title="View Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="deleteCategory(${id})"
                                            class="w-7 h-7 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                            title="Delete Category">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            `;
                        },
                        responsive: 0
                    }
                ],
                rowFormatter: function(row) {
                    const rowEl = row.getElement();
                    rowEl.classList.add('hover:bg-gray-50');
                },
                rowSelectionChanged: function(data, rows) {
                    updateBulkActions(data.length);
                }
            });

            window.categoriesTable = categoriesTable;

            // Fix layout after table is built
            categoriesTable.on("tableBuilt", function() {
                console.log("Tabulator table built successfully");

                // Redraw table to ensure proper layout
                setTimeout(() => {
                    categoriesTable.redraw(true);
                }, 100);

                // Initialize table functionality
                initCategoriesSearch();
                initCategoriesExport();
                initCategoriesColumnVisibility();
                initBulkActions();

                // Add click event for select all checkbox
                $(document).on('click', '#selectAllCategories', function() {
                    if ($(this).is(':checked')) {
                        categoriesTable.selectRow();
                    } else {
                        categoriesTable.deselectRow();
                    }
                });

                // Status toggle event delegation
                $(document).on('change', '.toggle-category-status', function(e) {
                    const categoryId = $(this).data('id');
                    const isActive = $(this).is(':checked');
                    console.log('Toggling category status:', categoryId, 'to', isActive);
                    toggleCategoryStatus(categoryId, isActive);
                });
            });
        }

        // Load categories data and initialize Tabulator
        async function loadCategoriesData(page = 1, perPage = 10) {
            try {
                const response = await axiosInstance.get('/categories', {
                    params: {
                        page: page,
                        per_page: perPage,
                        sort: 'sort_order',
                        direction: 'asc'
                    }
                });


                if (response.data.success) {
                    // CORRECTED: Access data from response.data.data
                    const categories = response.data.data.data || response.data.data || [];
                    const meta = response.data.data.meta || response.data.meta || {};

                    // Update pagination info
                    currentPage = meta.current_page || 1;
                    perPage = meta.per_page || 10;

                    // Initialize or update Tabulator
                    if (!categoriesTable) {
                        initializeCategoriesTable(categories);
                    } else {
                        categoriesTable.setData(categories);
                        updatePaginationInfo(meta);
                    }
                } else {
                    toastr.error('Failed to load categories: ' + (response.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                if (error.response?.status === 500) {
                    toastr.error('Server error while loading categories');
                } else {
                    toastr.error('Failed to load categories. Check console for details.');
                }

                // Initialize table with empty data if error
                if (!categoriesTable) {
                    initializeCategoriesTable([]);
                }
            }
        }

        // Update pagination info
        function updatePaginationInfo(meta) {
            console.log('Pagination updated:', meta);
            // You can update custom pagination UI here if needed
        }

        // Load statistics from API
        async function loadStatistics() {
            console.log('Loading statistics from API...');

            try {
                const response = await axiosInstance.get('/categories/statistics');
                console.log('Statistics API Response:', response.data);

                if (response.data.success) {
                    const stats = response.data.data;
                    document.getElementById('totalCategories').textContent = stats.total_categories || 0;
                    document.getElementById('activeCategories').textContent = stats.active_categories || 0;
                    document.getElementById('mainCategories').textContent = stats.main_categories || 0;
                    document.getElementById('popularCategory').textContent = stats.popular_category ?
                        stats.popular_category.name : '-';
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
                toastr.error('Failed to load statistics');
            }
        }

        // Load parent categories for dropdown
        async function loadParentCategories(excludeId = null) {
            console.log('Loading parent categories for dropdown...');

            try {
                const params = {};
                if (excludeId) {
                    params.exclude_id = excludeId;
                }

                const response = await axiosInstance.get('/categories/dropdown', {
                    params
                });
                console.log('Parent categories API Response:', response.data);

                if (response.data.success) {
                    const categories = response.data.data;
                    const select = document.getElementById('parent_id');
                    if (select) {
                        select.innerHTML = '<option value="">No Parent (Main Category)</option>';

                        // Recursive function to add options
                        function addOptions(categoryList, level = 0) {
                            categoryList.forEach(category => {
                                const prefix = '— '.repeat(level);
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.textContent = prefix + category.name;
                                select.appendChild(option);

                                // Add children recursively
                                if (category.children && category.children.length > 0) {
                                    addOptions(category.children, level + 1);
                                }
                            });
                        }

                        addOptions(categories);
                    }
                }
            } catch (error) {
                console.error('Error loading parent categories:', error);
                toastr.error('Failed to load parent categories');
            }
        }

        // ============================
        // BULK ACTIONS SYSTEM
        // ============================

        function initBulkActions() {
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const selectAllCategories = document.getElementById('selectAllCategories');
            const clearSelection = document.getElementById('clearSelection');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const tabulatorBulkDeleteBtn = document.getElementById('tabulatorBulkDeleteBtn');
            const bulkActionsBtn = document.getElementById('bulkActionsBtn');

            // Update selected count and show/hide bulk actions bar
            function updateBulkActions(selectedCountNum) {
                if (selectedCount) {
                    selectedCount.textContent = selectedCountNum;
                }

                if (bulkActionsBar) {
                    if (selectedCountNum > 0) {
                        bulkActionsBar.classList.remove('hidden');
                        bulkActionsBar.classList.add('flex');
                        // Scroll to bottom for mobile
                        if (window.innerWidth < 768) {
                            setTimeout(() => {
                                bulkActionsBar.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'end'
                                });
                            }, 100);
                        }
                    } else {
                        bulkActionsBar.classList.remove('flex');
                        bulkActionsBar.classList.add('hidden');
                    }
                }

                // Update select all checkbox
                const totalRows = categoriesTable.getDataCount();
                if (selectAllCategories) {
                    selectAllCategories.checked = selectedCountNum === totalRows && totalRows > 0;
                    selectAllCategories.indeterminate = selectedCountNum > 0 && selectedCountNum < totalRows;
                }

                // Update tabulator bulk delete button
                if (tabulatorBulkDeleteBtn) {
                    if (selectedCountNum > 0) {
                        tabulatorBulkDeleteBtn.classList.remove('hidden');
                        tabulatorBulkDeleteBtn.innerHTML = `<i class="fas fa-trash mr-2"></i>Delete (${selectedCountNum})`;
                    } else {
                        tabulatorBulkDeleteBtn.classList.add('hidden');
                    }
                }
            }

            // Select All functionality
            if (selectAllCategories) {
                selectAllCategories.addEventListener('click', function() {
                    if (this.checked) {
                        categoriesTable.selectRow();
                    } else {
                        categoriesTable.deselectRow();
                    }
                });
            }

            // Row selection event
            categoriesTable.on("rowSelectionChanged", function(data, rows) {
                updateBulkActions(data.length);
            });

            // Clear selection
            if (clearSelection) {
                clearSelection.addEventListener('click', function() {
                    categoriesTable.deselectRow();
                    if (selectAllCategories) {
                        selectAllCategories.checked = false;
                        selectAllCategories.indeterminate = false;
                    }
                    updateBulkActions(0);
                    toastr.info('Selection cleared');
                });
            }

            // Bulk Delete Function for both buttons
            async function handleBulkDelete() {
                const selectedRows = categoriesTable.getSelectedRows();
                const selectedIds = selectedRows.map(row => row.getData().id);

                if (selectedIds.length === 0) {
                    toastr.warning('Please select at least one category to delete.');
                    return;
                }

                const itemName = 'category';
                const itemCount = selectedIds.length;

                // Get selected categories data
                const selectedCategories = selectedRows.map(row => row.getData());

                Swal.fire({
                    title: `Delete ${itemCount} ${itemName}${itemCount > 1 ? 'ies' : 'y'}?`,
                    html: `
                <div class="text-left space-y-4">
                    <p class="text-gray-700">You are about to delete <strong>${itemCount}</strong> categor${itemCount > 1 ? 'ies' : 'y'}.</p>

                    <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                        <div class="flex items-center text-rose-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-semibold">Warning</span>
                        </div>
                        <ul class="text-sm text-rose-700 space-y-1 list-disc pl-5">
                            <li>Subcategories will become main categories</li>
                            <li>Products will keep their category association</li>
                            <li>Category images will be permanently deleted</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Selected categor${itemCount > 1 ? 'ies' : 'y'}:</p>
                        <div class="max-h-32 overflow-y-auto">
                            ${getSelectedCategoriesPreview(selectedCategories)}
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-amber-50 rounded-lg border border-amber-200">
                        <input type="checkbox" id="confirmDelete" class="w-4 h-4 text-rose-600 bg-white border-gray-300 rounded focus:ring-rose-500">
                        <label for="confirmDelete" class="ml-3 text-sm font-medium text-amber-800">
                            I understand this action cannot be undone
                        </label>
                    </div>
                </div>
            `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Delete ${itemCount} categor${itemCount > 1 ? 'ies' : 'y'}`,
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    width: '600px',
                    customClass: {
                        popup: 'mobile-swal',
                        actions: 'flex gap-2',
                        confirmButton: 'btn-danger',
                        cancelButton: 'btn-secondary'
                    },
                    preConfirm: () => {
                        if (!document.getElementById('confirmDelete').checked) {
                            Swal.showValidationMessage(
                                'Please confirm that you understand this action cannot be undone.');
                            return false;
                        }
                        return {
                            ids: selectedIds
                        };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await performBulkDelete(selectedIds, itemName);
                    }
                });
            }

            // Helper: Get selected categories preview HTML
            function getSelectedCategoriesPreview(selectedCategories) {
                if (selectedCategories.length === 0) return '<p class="text-sm text-gray-500">No categories selected</p>';

                return selectedCategories.map(category => `
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                        ${category.image_url || category.image ?
                            `<img src="${category.image_url || category.image}" alt="${category.name}" class="w-full h-full object-cover">` :
                            `<i class="fas fa-folder text-gray-400 text-xs"></i>`
                        }
                    </div>
                    <div class="min-w-0">
                        <span class="text-sm text-gray-900 truncate block">${category.name || 'Unnamed'}</span>
                        <span class="text-xs text-gray-500">${category.products_count || 0} products</span>
                        ${category.parent_name ? `<span class="text-xs text-gray-400">Parent: ${category.parent_name}</span>` : ''}
                    </div>
                </div>
                <span class="text-xs text-gray-500">ID: ${category.id}</span>
            </div>
        `).join('');
            }

            // Helper: Perform bulk delete
            async function performBulkDelete(selectedIds, itemName) {
                const ids = selectedIds.map(id => parseInt(id));

                Swal.fire({
                    title: 'Deleting...',
                    text: `Please wait while we delete ${ids.length} categor${ids.length > 1 ? 'ies' : 'y'}`,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await axiosInstance.post('/categories/bulk-delete', {
                        ids: ids
                    });

                    if (response.data.success) {
                        const deletedCount = response.data.data.deleted_count;

                        // Clear selection
                        categoriesTable.deselectRow();
                        if (selectAllCategories) {
                            selectAllCategories.checked = false;
                            selectAllCategories.indeterminate = false;
                        }
                        if (bulkActionsBar) {
                            bulkActionsBar.classList.add('hidden');
                        }

                        // Refresh data
                        await Promise.all([
                            loadCategoriesData(),
                            loadStatistics(),
                            loadParentCategories()
                        ]);

                        Swal.close();
                        toastr.success(`Successfully deleted ${deletedCount} categor${deletedCount > 1 ? 'ies' : 'y'}`);

                        const remainingCount = categoriesTable.getDataCount();
                        if (remainingCount === 0) {
                            toastr.info('All categories have been deleted.');
                        }
                    } else {
                        Swal.close();
                        toastr.error(response.data.message || 'Failed to delete categories');
                    }
                } catch (error) {
                    Swal.close();
                    if (error.response?.status === 400) {
                        toastr.error(error.response.data.message ||
                        'Cannot delete categories with associated products');
                    } else {
                        toastr.error('Failed to delete categories');
                    }
                }
            }

            // Attach bulk delete to both buttons
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', handleBulkDelete);
            }
            if (tabulatorBulkDeleteBtn) {
                tabulatorBulkDeleteBtn.addEventListener('click', handleBulkDelete);
            }

            // Bulk actions modal
            if (bulkActionsBtn) {
                bulkActionsBtn.addEventListener('click', function() {
                    const selectedRows = categoriesTable.getSelectedRows();

                    if (selectedRows.length === 0) {
                        toastr.warning('Please select at least one category');
                        return;
                    }

                    const selectedCountInfo = document.getElementById('selectedCountInfo');
                    if (selectedCountInfo) {
                        selectedCountInfo.textContent =
                            `${selectedRows.length} categor${selectedRows.length > 1 ? 'ies' : 'y'} selected`;
                    }

                    const bulkActionsModal = document.getElementById('bulkActionsModal');
                    if (bulkActionsModal) {
                        bulkActionsModal.classList.remove('hidden');
                    }
                });
            }

            // Keyboard shortcuts for bulk actions
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + A to select all
                if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                    e.preventDefault();
                    categoriesTable.selectRow();
                }

                // Escape to clear selection
                if (e.key === 'Escape') {
                    categoriesTable.deselectRow();
                    if (selectAllCategories) {
                        selectAllCategories.checked = false;
                        selectAllCategories.indeterminate = false;
                    }
                    updateBulkActions(0);
                }

                // Delete key to trigger bulk delete (when selection exists)
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    const selectedRows = categoriesTable.getSelectedRows();
                    if (selectedRows.length > 0) {
                        e.preventDefault();
                        handleBulkDelete();
                    }
                }
            });

            // Close bulk actions when clicking outside
            document.addEventListener('click', function(e) {
                if (bulkActionsBar && !bulkActionsBar.contains(e.target)) {
                    // Don't close if clicking on the bulk delete button in toolbar
                    const toolbarBulkBtn = document.getElementById('tabulatorBulkDeleteBtn');
                    if (!toolbarBulkBtn || !toolbarBulkBtn.contains(e.target)) {
                        // Optional: Uncomment if you want clicking outside to clear selection
                        // categoriesTable.deselectRow();
                        // updateBulkActions(0);
                    }
                }
            });

            // Initialize
            updateBulkActions(0);
        }

        // Search functionality
        function initCategoriesSearch() {
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value;

                    // Clear previous timeout
                    clearTimeout(searchTimeout);

                    // Set new timeout
                    searchTimeout = setTimeout(() => {
                        if (searchTerm.length >= 2 || searchTerm === '') {
                            categoriesTable.setFilter([
                                [{
                                        field: "name",
                                        type: "like",
                                        value: searchTerm
                                    },
                                    {
                                        field: "description",
                                        type: "like",
                                        value: searchTerm
                                    },
                                    {
                                        field: "slug",
                                        type: "like",
                                        value: searchTerm
                                    }
                                ]
                            ]);
                        }
                    }, 500);
                });
            }
        }

        // Column visibility
        function initCategoriesColumnVisibility() {
            const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
            if (!columnVisibilityBtn || !categoriesTable) return;

            const columnMenu = document.createElement('div');
            columnMenu.className =
                'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

            const columns = categoriesTable.getColumnDefinitions();

            columns.forEach((column, index) => {
                if (index === 0) return; // skip checkbox column

                const field = column.field;
                const columnBtn = document.createElement('button');
                columnBtn.className =
                    'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
                columnBtn.innerHTML = `
                <input type="checkbox" class="mr-2" ${categoriesTable.getColumn(field).isVisible() ? 'checked' : ''}>
                ${column.title}
            `;

                columnBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const col = categoriesTable.getColumn(field);
                    const checkbox = this.querySelector('input');
                    col.toggle();
                    setTimeout(() => {
                        checkbox.checked = col.isVisible();
                    }, 10);
                });

                columnMenu.appendChild(columnBtn);
            });

            // Toggle menu
            columnVisibilityBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                columnMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
                    columnMenu.classList.add('hidden');
                }
            });

            columnVisibilityBtn.parentElement.appendChild(columnMenu);
        }

        // ==========================================
        // EXPORT SYSTEM (EXCEL, PDF, CLEAN PRINT)
        // ==========================================

        function escapeCategoryHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        async function getCategoriesExportData() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput ? searchInput.value.trim() : '';

            try {
                const params = {
                    per_page: 1000,
                    sort: 'sort_order',
                    direction: 'asc'
                };
                if (searchTerm) {
                    params.search = searchTerm;
                }
                const res = await axiosInstance.get('/categories', { params });
                if (res.data && res.data.success) {
                    const data = res.data.data.data || res.data.data || [];
                    if (Array.isArray(data) && data.length > 0) {
                        return data;
                    }
                }
            } catch (err) {
                console.warn('API export fetch failed, falling back to tabulator', err);
            }

            if (categoriesTable && typeof categoriesTable.getData === 'function') {
                const tabData = categoriesTable.getData("active");
                if (tabData && tabData.length > 0) return tabData;
                return categoriesTable.getData() || [];
            }
            return [];
        }

        function exportCategoriesToExcel(data) {
            if (!data || data.length === 0) {
                toastr.warning('No categories data to export');
                return;
            }

            if (typeof XLSX === 'undefined') {
                toastr.error('Excel library is still loading, please retry in a second.');
                return;
            }

            const rows = data.map((item, index) => {
                const isSub = item.parent_id !== null && item.parent_id !== 0 && item.parent_id !== '0';
                const type = isSub ? 'Subcategory' : 'Main Category';
                const parentName = item.parent_name || (isSub ? 'Subcategory' : 'None (Main)');
                const isActive = (item.status === true || item.status === 1 || item.status === '1' || item.status === 'true');

                return {
                    '#': index + 1,
                    'Category ID': item.id,
                    'Category Name': item.name || '',
                    'Slug': item.slug ? `/${item.slug}` : '',
                    'Type': type,
                    'Parent Category': parentName,
                    'Description': item.description || '',
                    'Products Count': item.products_count ?? 0,
                    'Subcategories Count': item.children_count ?? 0,
                    'Status': isActive ? 'Active' : 'Inactive',
                    'Sort Order': item.sort_order ?? 0,
                    'Created Date': item.created_at_formatted || item.created_at || ''
                };
            });

            const worksheet = XLSX.utils.json_to_sheet(rows);

            worksheet['!cols'] = [
                { wch: 5 },  // #
                { wch: 14 }, // Category ID
                { wch: 28 }, // Category Name
                { wch: 22 }, // Slug
                { wch: 16 }, // Type
                { wch: 22 }, // Parent Category
                { wch: 32 }, // Description
                { wch: 16 }, // Products Count
                { wch: 20 }, // Subcategories Count
                { wch: 12 }, // Status
                { wch: 12 }, // Sort Order
                { wch: 18 }  // Created Date
            ];

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Categories');

            const dateStr = new Date().toISOString().slice(0, 10);
            XLSX.writeFile(workbook, `KNOTELLE_Categories_${dateStr}.xlsx`);
            toastr.success(`Exported ${data.length} categories to Excel successfully!`);
        }

        function generateCategoriesCleanHtml(data) {
            let rowsHtml = '';
            const activeCount = data.filter(c => c.status === true || c.status === 1 || c.status === '1' || c.status === 'true').length;
            const inactiveCount = data.length - activeCount;
            const mainCount = data.filter(c => !c.parent_id || c.parent_id === 0 || c.parent_id === '0').length;
            const subCount = data.length - mainCount;

            data.forEach((item, idx) => {
                const isSub = item.parent_id !== null && item.parent_id !== 0 && item.parent_id !== '0';
                const typeText = isSub ? 'Subcategory' : 'Main Category';
                const isActive = (item.status === true || item.status === 1 || item.status === '1' || item.status === 'true');
                const bg = idx % 2 === 1 ? '#fafaf9' : '#ffffff';

                rowsHtml += `
                    <tr style="background-color: ${bg};">
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; color: #64748b; font-size: 10px;">${idx + 1}</td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; font-weight: 700; color: #475569; font-size: 11px;">#${item.id}</td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 10px;">
                            <div style="font-weight: 700; color: #0f172a; font-size: 12px;">${escapeCategoryHtml(item.name || 'Untitled')}</div>
                            <div style="font-size: 10px; color: #64748b; margin-top: 1px;">/${escapeCategoryHtml(item.slug || '')}</div>
                            ${item.description ? `<div style="font-size: 9.5px; color: #94a3b8; margin-top: 2px;">${escapeCategoryHtml(item.description)}</div>` : ''}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 10px; font-size: 11px;">
                            <span style="font-weight: 600; color: ${isSub ? '#0369a1' : '#0f172a'};">${typeText}</span>
                            ${isSub && item.parent_name ? `<div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">Parent: <strong>${escapeCategoryHtml(item.parent_name)}</strong></div>` : ''}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center;">
                            <span style="font-weight: 700; color: #0f172a; font-size: 11px;">${item.products_count ?? 0}</span>
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center;">
                            <span style="font-weight: 700; color: #0f172a; font-size: 11px;">${item.children_count ?? 0}</span>
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center;">
                            ${isActive 
                                ? '<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 9.5px; font-weight: 700; background: #d1fae5; color: #065f46;">Active</span>'
                                : '<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 9.5px; font-weight: 700; background: #f1f5f9; color: #475569;">Inactive</span>'
                            }
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; font-size: 11px; color: #334155;">
                            ${item.sort_order ?? 0}
                        </td>
                        <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; font-size: 10px; color: #64748b; white-space: nowrap;">
                            ${escapeCategoryHtml(item.created_at_formatted || '-')}
                        </td>
                    </tr>
                `;
            });

            if (data.length === 0) {
                rowsHtml = `<tr><td colspan="9" style="text-align: center; padding: 24px; color: #64748b; font-size: 12px;">No categories available to display</td></tr>`;
            }

            const printDate = new Date().toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) + ' - ' + new Date().toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            return `
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta charset="utf-8">
                        <title>KNOTELLE - Category Management Directory Report</title>
                        <style>
                            @page {
                                size: A4 landscape;
                                margin: 10mm 10mm 10mm 10mm;
                            }
                            * {
                                box-sizing: border-box;
                            }
                            body {
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                                margin: 0;
                                padding: 12px;
                                color: #1e293b;
                                background: #ffffff;
                                font-size: 11px;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            .header {
                                display: flex;
                                justify-content: space-between;
                                align-items: flex-start;
                                border-bottom: 2px solid #dc2626;
                                padding-bottom: 12px;
                                margin-bottom: 12px;
                            }
                            .brand {
                                display: flex;
                                align-items: flex-start;
                                gap: 12px;
                            }
                            .brand img {
                                height: 46px;
                                width: auto;
                                object-fit: contain;
                            }
                            .brand h2 {
                                margin: 0;
                                font-size: 18px;
                                color: #dc2626;
                                font-weight: 800;
                                letter-spacing: 0.5px;
                            }
                            .brand p {
                                margin: 2px 0 0 0;
                                font-size: 10px;
                                color: #475569;
                                line-height: 1.35;
                            }
                            .meta {
                                text-align: right;
                            }
                            .badge {
                                display: inline-block;
                                padding: 2px 9px;
                                font-size: 10px;
                                font-weight: 800;
                                background: #fee2e2;
                                color: #dc2626;
                                border-radius: 9999px;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            }
                            .report-title {
                                margin: 4px 0 2px 0;
                                font-size: 13px;
                                font-weight: 700;
                                color: #0f172a;
                            }
                            .meta p {
                                margin: 2px 0 0 0;
                                font-size: 10px;
                                color: #64748b;
                            }
                            .summary-bar {
                                display: flex;
                                gap: 16px;
                                background: #f8fafc;
                                border: 1px solid #e2e8f0;
                                border-radius: 6px;
                                padding: 6px 12px;
                                margin-bottom: 12px;
                                font-size: 10.5px;
                            }
                            .summary-item {
                                color: #475569;
                            }
                            .summary-item strong {
                                color: #0f172a;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                font-size: 10.5px;
                            }
                            th {
                                background-color: #f1f5f9;
                                color: #334155;
                                font-weight: 700;
                                text-transform: uppercase;
                                font-size: 9.5px;
                                letter-spacing: 0.3px;
                                padding: 7px 8px;
                                border: 1px solid #cbd5e1;
                                text-align: left;
                            }
                            .footer {
                                margin-top: 14px;
                                border-top: 1px solid #e2e8f0;
                                padding-top: 8px;
                                display: flex;
                                justify-content: space-between;
                                font-size: 9.5px;
                                color: #64748b;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <div class="brand">
                                <img src="{{ asset('images/logo/knotelle-logo.png') }}?v=2" alt="KNOTELLE" onerror="this.style.display='none'">
                                <div>
                                    <h2>KNOTELLE</h2>
                                    <p>
                                        Handcrafted with Love India<br>
                                        support@knotelle.in &bull; +91 9773055555
                                    </p>
                                </div>
                            </div>
                            <div class="meta">
                                <span class="badge">Category Report</span>
                                <div class="report-title">Category Directory Management</div>
                                <p>Generated: ${printDate}</p>
                            </div>
                        </div>

                        <div class="summary-bar">
                            <span class="summary-item">Total Categories: <strong>${data.length}</strong></span>
                            <span class="summary-item">Active: <strong>${activeCount}</strong></span>
                            <span class="summary-item">Inactive: <strong>${inactiveCount}</strong></span>
                            <span class="summary-item">Main Categories: <strong>${mainCount}</strong></span>
                            <span class="summary-item">Subcategories: <strong>${subCount}</strong></span>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 35px; text-align: center;">#</th>
                                    <th style="width: 50px; text-align: center;">ID</th>
                                    <th>Category</th>
                                    <th style="width: 160px;">Type / Parent</th>
                                    <th style="width: 75px; text-align: center;">Products</th>
                                    <th style="width: 75px; text-align: center;">Subcats</th>
                                    <th style="width: 80px; text-align: center;">Status</th>
                                    <th style="width: 60px; text-align: center;">Order</th>
                                    <th style="width: 100px; text-align: center;">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rowsHtml}
                            </tbody>
                        </table>

                        <div class="footer">
                            <span>Total Records: ${data.length} Categories</span>
                            <span>KNOTELLE Admin Management System &bull; Confidential</span>
                        </div>
                    </body>
                </html>
            `;
        }

        function printCategoriesReport(data) {
            if (!data || data.length === 0) {
                toastr.warning('No categories data to print');
                return;
            }

            const printFrame = document.createElement('iframe');
            printFrame.style.position = 'fixed';
            printFrame.style.right = '0';
            printFrame.style.bottom = '0';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = '0';
            document.body.appendChild(printFrame);

            const frameDoc = printFrame.contentWindow.document;
            frameDoc.open();
            frameDoc.write(generateCategoriesCleanHtml(data));
            frameDoc.close();

            printFrame.contentWindow.focus();
            setTimeout(() => {
                printFrame.contentWindow.print();
                setTimeout(() => {
                    if (document.body.contains(printFrame)) {
                        document.body.removeChild(printFrame);
                    }
                }, 1500);
            }, 350);
        }

        async function exportCategoriesToPdf(data) {
            if (!data || data.length === 0) {
                toastr.warning('No categories data to export');
                return;
            }

            const dateStr = new Date().toISOString().slice(0, 10);
            const filename = `KNOTELLE_Categories_Report_${dateStr}.pdf`;

            toastr.info('Generating PDF report...');

            // Method 1: Vector PDF using jsPDF + autoTable
            if (window.jspdf && typeof window.jspdf.jsPDF === 'function') {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({
                        orientation: 'landscape',
                        unit: 'mm',
                        format: 'a4'
                    });

                    // Red brand top border line
                    doc.setFillColor(220, 38, 38);
                    doc.rect(14, 10, 269, 1.5, 'F');

                    // Header - Left Side (Brand)
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(220, 38, 38);
                    doc.text('KNOTELLE', 14, 18);

                    doc.setFontSize(8);
                    doc.setFont('helvetica', 'normal');
                    doc.setTextColor(71, 85, 105);
                    doc.text('Handcrafted with Love India | support@knotelle.in | +91 9773055555', 14, 23);

                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(15, 23, 42);
                    doc.text('Category Management Directory Report', 14, 30);

                    // Header - Right Side (Badge & Meta)
                    doc.setFillColor(254, 226, 226);
                    doc.roundedRect(243, 13, 40, 6, 1, 1, 'F');
                    doc.setTextColor(220, 38, 38);
                    doc.setFontSize(8);
                    doc.setFont('helvetica', 'bold');
                    doc.text('ADMIN REPORT', 263, 17.5, { align: 'center' });

                    const printDateStr = new Date().toLocaleDateString('en-IN', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    }) + ' - ' + new Date().toLocaleTimeString('en-IN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                    doc.setFontSize(8);
                    doc.setFont('helvetica', 'normal');
                    doc.setTextColor(100, 116, 139);
                    doc.text(`Generated: ${printDateStr}`, 283, 24, { align: 'right' });

                    const activeCount = data.filter(c => c.status === true || c.status === 1 || c.status === '1' || c.status === 'true').length;
                    const mainCount = data.filter(c => !c.parent_id || c.parent_id === 0 || c.parent_id === '0').length;
                    doc.text(`Total: ${data.length} | Active: ${activeCount} | Main: ${mainCount}`, 283, 29, { align: 'right' });

                    // Table Data Rows
                    const tableRows = data.map((item, idx) => {
                        const isSub = item.parent_id !== null && item.parent_id !== 0 && item.parent_id !== '0';
                        const typeText = isSub ? 'Subcategory' : 'Main Category';
                        const parentText = item.parent_name || (isSub ? 'Subcategory' : 'None');
                        const isActive = (item.status === true || item.status === 1 || item.status === '1' || item.status === 'true');

                        return [
                            idx + 1,
                            `#${item.id}`,
                            item.name || '-',
                            item.slug ? `/${item.slug}` : '-',
                            typeText,
                            parentText,
                            item.products_count ?? 0,
                            item.children_count ?? 0,
                            isActive ? 'Active' : 'Inactive',
                            item.sort_order ?? 0,
                            item.created_at_formatted || '-'
                        ];
                    });

                    doc.autoTable({
                        startY: 34,
                        head: [['#', 'ID', 'Category Name', 'Slug', 'Type', 'Parent', 'Products', 'Subcats', 'Status', 'Order', 'Created']],
                        body: tableRows,
                        theme: 'grid',
                        headStyles: {
                            fillColor: [241, 245, 249],
                            textColor: [30, 41, 59],
                            fontSize: 8.5,
                            fontStyle: 'bold',
                            halign: 'left',
                            lineWidth: 0.1,
                            lineColor: [203, 213, 225]
                        },
                        bodyStyles: {
                            fontSize: 8,
                            textColor: [30, 41, 59],
                            lineWidth: 0.1,
                            lineColor: [226, 232, 240]
                        },
                        alternateRowStyles: {
                            fillColor: [250, 250, 249]
                        },
                        columnStyles: {
                            0: { halign: 'center', cellWidth: 10 },
                            1: { halign: 'center', cellWidth: 14, fontStyle: 'bold' },
                            2: { fontStyle: 'bold', cellWidth: 42 },
                            3: { textColor: [100, 116, 139], cellWidth: 32 },
                            4: { cellWidth: 26 },
                            5: { cellWidth: 32 },
                            6: { halign: 'center', cellWidth: 20 },
                            7: { halign: 'center', cellWidth: 20 },
                            8: { halign: 'center', cellWidth: 20 },
                            9: { halign: 'center', cellWidth: 16 },
                            10: { halign: 'center', cellWidth: 24 }
                        },
                        didParseCell: function(cellData) {
                            if (cellData.section === 'body' && cellData.column.index === 8) {
                                if (cellData.cell.raw === 'Active') {
                                    cellData.cell.styles.textColor = [6, 95, 70];
                                    cellData.cell.styles.fontStyle = 'bold';
                                } else {
                                    cellData.cell.styles.textColor = [100, 116, 139];
                                }
                            }
                        },
                        didDrawPage: function(pageData) {
                            const pageCount = doc.internal.getNumberOfPages();
                            doc.setFontSize(8);
                            doc.setTextColor(148, 163, 184);
                            doc.text('KNOTELLE Admin Management System • Confidential', 14, 202);
                            doc.text(`Page ${pageData.pageNumber} of ${pageCount}`, 283, 202, { align: 'right' });
                        },
                        margin: { left: 14, right: 14, bottom: 14 }
                    });

                    doc.save(filename);
                    toastr.success(`Downloaded ${filename} successfully!`);
                    return;
                } catch (e) {
                    console.warn('jsPDF autoTable failed, trying html2pdf', e);
                }
            }

            // Method 2: html2pdf fallback
            if (typeof html2pdf !== 'undefined') {
                try {
                    const htmlContent = generateCategoriesCleanHtml(data);
                    const container = document.createElement('div');
                    container.innerHTML = htmlContent;
                    document.body.appendChild(container);

                    const opt = {
                        margin: [10, 10, 10, 10],
                        filename: filename,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true, logging: false },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                    };

                    await html2pdf().set(opt).from(container).save();
                    document.body.removeChild(container);
                    toastr.success(`Downloaded ${filename} successfully!`);
                    return;
                } catch (err) {
                    console.error('html2pdf failed', err);
                }
            }

            // Fallback: If libraries fail, open print dialog
            toastr.info('Opening print dialog for PDF saving...');
            printCategoriesReport(data);
        }

        async function exportCategories(type) {
            toastr.info('Preparing categories data for export...');
            try {
                const data = await getCategoriesExportData();
                if (!data || data.length === 0) {
                    toastr.warning('No categories found to export');
                    return;
                }

                if (type === 'excel') {
                    exportCategoriesToExcel(data);
                } else if (type === 'pdf') {
                    await exportCategoriesToPdf(data);
                } else if (type === 'print') {
                    printCategoriesReport(data);
                }
            } catch (e) {
                console.error('Export error:', e);
                toastr.error('Failed to export categories');
            }
        }

        // Export initialization & global print hooking
        function initCategoriesExport() {
            // Hook native window.print so browser print / shortcuts invoke clean table print
            window.print = function() {
                exportCategories('print');
            };

            // Keyboard shortcut Ctrl+P or Cmd+P
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'p') {
                    e.preventDefault();
                    exportCategories('print');
                }
            });
        }

        // Close bulk actions modal
        function closeBulkActions() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
        }

        // Show create category modal
        async function showCreateCategoryModal() {
            try {
                isEditing = false;
                document.getElementById('modalTitle').textContent = 'Add New Category';
                document.getElementById('submitText').textContent = 'Save Category';
                document.getElementById('categoryForm').reset();
                document.getElementById('categoryId').value = '';
                document.getElementById('image_id').value = '';
                document.getElementById('imagePreview').innerHTML =
                    '<i class="fas fa-image text-gray-400 text-2xl"></i>';
                document.getElementById('status').checked = true;
                document.getElementById('sort_order').value = 0;
                selectedMediaId = null;
                selectedMediaUrl = null;

                // Load parent categories (no exclusion)
                await loadParentCategories();

                // Clear errors
                ['nameError', 'slugError'].forEach(errorId => {
                    const errorElement = document.getElementById(errorId);
                    if (errorElement) {
                        errorElement.classList.add('hidden');
                        errorElement.textContent = '';
                    }
                });

                document.getElementById('categoryModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error opening create modal:', error);
                toastr.error('Failed to open create category modal');
            }
        }

        // Close category modal
        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function sanitizeImageUrl(rawUrl) {
            if (!rawUrl) return '/images/logo/Logo_1.png';
            let url = String(rawUrl).trim();
            url = url.replace(/^https?:\/\/[^\/]+/, '');
            if (url.startsWith('/storage/images/')) {
                url = url.replace('/storage/images/', '/images/');
            } else if (url.startsWith('storage/images/')) {
                url = url.replace('storage/images/', '/images/');
            } else if (url.startsWith('/storage/')) {
                url = url.replace('/storage/', '/images/');
            } else if (url.startsWith('storage/')) {
                url = url.replace('storage/', '/images/');
            }
            if (!url.startsWith('http://') && !url.startsWith('https://') && !url.startsWith('/')) {
                url = '/' + url;
            }
            return url;
        }

        // Open media library
        async function openMediaLibrary() {
            try {
                const response = await axiosInstance.get('/media', {
                    params: {
                        per_page: 50,
                        type: 'image'
                    }
                });

                if (response.data.success) {
                    const mediaItems = response.data.data?.data || response.data.data || response.data || [];
                    const mediaGrid = document.getElementById('mediaGrid');
                    mediaGrid.innerHTML = '';

                    mediaItems.forEach(media => {
                        const rawUrl = media.thumb_url || media.thumbnail_url || media.url || media.full_url || media.file_path || media.path;
                        const mediaUrl = sanitizeImageUrl(rawUrl);
                        if (!mediaUrl) return;

                        const mediaItem = document.createElement('div');
                        mediaItem.className = 'relative group cursor-pointer';
                        mediaItem.dataset.id = media.id;
                        mediaItem.dataset.url = mediaUrl;

                        mediaItem.innerHTML = `
                <div class="relative overflow-hidden rounded-lg border-2 border-transparent group-hover:border-indigo-500 transition-colors">
                    <img src="${mediaUrl}"
                         alt="${media.name || media.file_name || 'Image'}"
                         class="w-full h-32 object-cover bg-stone-100"
                         onerror="this.onerror=null;this.src='/images/logo/Logo_1.png'">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity"></div>
                    <div class="absolute top-2 right-2 hidden group-hover:block">
                        <div class="w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600 truncate">${media.name || media.file_name || 'Untitled'}</p>
            `;

                        mediaItem.addEventListener('click', function() {
                            // Remove selection from all items
                            document.querySelectorAll('#mediaGrid > div').forEach(item => {
                                item.classList.remove('selected-media');
                                item.querySelector('.border-2').classList.remove(
                                    'border-indigo-500');
                                item.querySelector('.border-2').classList.add(
                                    'border-transparent');
                            });

                            // Select this item
                            this.classList.add('selected-media');
                            this.querySelector('.border-2').classList.remove('border-transparent');
                            this.querySelector('.border-2').classList.add('border-indigo-500');

                            selectedMediaId = this.dataset.id;
                            selectedMediaUrl = this.dataset.url;
                        });

                        mediaGrid.appendChild(mediaItem);
                    });

                    // Add message if no media
                    if (mediaGrid.children.length === 0) {
                        mediaGrid.innerHTML =
                            '<div class="col-span-full text-center py-8 text-gray-500">No images found</div>';
                    }

                    document.getElementById('mediaLibraryModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading media:', error);
                toastr.error('Failed to load media library');
                document.getElementById('mediaGrid').innerHTML =
                    '<div class="col-span-full text-center py-8 text-rose-500">Error loading media</div>';
            }
        }

        // Close media library
        function closeMediaLibrary() {
            document.getElementById('mediaLibraryModal').classList.add('hidden');
        }

        // Select media
        function selectMedia() {
            if (selectedMediaId && selectedMediaUrl) {
                document.getElementById('image_id').value = selectedMediaId;
                const preview = document.getElementById('imagePreview');
                preview.innerHTML =
                    `<img src="${selectedMediaUrl}" alt="Selected image" class="w-full h-full object-cover">`;
                closeMediaLibrary();
            } else {
                toastr.warning('Please select an image first');
            }
        }

        // Save category (create or update)
        async function saveCategory() {
            const form = document.getElementById('categoryForm');
            if (!form) return;

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
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
                // Collect form data
                const formData = {
                    name: document.getElementById('name').value,
                    slug: document.getElementById('slug').value,
                    description: document.getElementById('description').value || null,
                    parent_id: document.getElementById('parent_id').value || null,
                    meta_title: document.getElementById('meta_title').value || null,
                    meta_description: document.getElementById('meta_description').value || null,
                    meta_keywords: document.getElementById('meta_keywords').value || null,
                    canonical_url: document.getElementById('canonical_url').value || null,
                    sort_order: parseInt(document.getElementById('sort_order').value) || 0,
                    status: document.getElementById('status').checked ? 1 : 0,
                    image_id: document.getElementById('image_id').value || null
                };

                // Add ID if editing
                if (isEditing) {
                    formData.id = document.getElementById('categoryId').value;
                }

                const method = isEditing ? 'put' : 'post';
                const url = isEditing ? `/categories/${formData.id}` : '/categories';

                const response = await axiosInstance({
                    method: method,
                    url: url,
                    data: formData,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeCategoryModal();

                    // Refresh all data from APIs
                    await Promise.all([
                        loadCategoriesData(),
                        loadStatistics(),
                        loadParentCategories()
                    ]);
                } else {
                    toastr.error(response.data.message || 'Failed to save category');
                }
            } catch (error) {
                console.error('Error saving category:', error);
                if (error.response && error.response.status === 422) {
                    // Validation errors
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const errorElement = document.getElementById(field + 'Error');
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                    toastr.error('Please fix the validation errors');
                } else {
                    toastr.error(error.response?.data?.message || 'Failed to save category');
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Navigate to the dedicated edit page.
        function editCategory(id) {
            window.location.assign('{{ url('/admin/categories') }}/' + encodeURIComponent(id) + '/edit');
        }

        // View category details - KEEP THIS AS MODAL
        async function viewCategory(id) {
            try {
                const response = await axiosInstance.get(`/categories/${id}`);

                if (response.data.success) {
                    const category = response.data.data;

                    let imageHtml = (category.image_url || category.image) ?
                        `<img src="${category.image_url || category.image}" alt="${category.name}" class="w-32 h-32 rounded-lg object-cover mb-4 mx-auto">` :
                        '';

                    let childrenHtml = '';
                    if (category.children && category.children.length > 0) {
                        childrenHtml = `
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 mb-2">Subcategories (${category.children.length})</h4>
                <div class="space-y-2">
                    ${category.children.map(child => `
                                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            ${child.image_url || child.image ? `<img src="${child.image_url || child.image}" alt="${child.name}" class="w-8 h-8 rounded object-cover">` : ''}
                                            <span class="text-sm">${child.name}</span>
                                        </div>
                                        <span class="text-xs text-gray-500">${child.products_count || 0} products</span>
                                    </div>
                                `).join('')}
                </div>
            </div>
        `;
                    }

                    Swal.fire({
                        title: category.name,
                        html: `
            <div class="text-left">
                ${imageHtml}
                <p class="mb-3"><strong>Slug:</strong> /${category.slug}</p>
                <p class="mb-3"><strong>Parent:</strong> ${category.parent_name || 'None (Main Category)'}</p>
                <p class="mb-3"><strong>Description:</strong> ${category.description || 'No description'}</p>
                <p class="mb-1"><strong>Status:</strong> <span class="capitalize">${category.status ? 'Active' : 'Inactive'}</span></p>
                <p class="mb-3"><strong>Products:</strong> ${category.products_count || 0}</p>
                <p class="mb-3"><strong>Sort Order:</strong> ${category.sort_order || 0}</p>
                ${childrenHtml}
                <p class="mb-3 text-sm text-gray-500"><strong>Created:</strong> ${new Date(category.created_at).toLocaleDateString()}</p>
            </div>
        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Edit',
                        cancelButtonText: 'Close',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to edit page when user clicks Edit
                            window.location.href = `/admin/categories/${id}/edit`;
                        }
                    });
                }
            } catch (error) {
                console.error('Error viewing category:', error);
                toastr.error('Failed to load category details');
            }
        }
        // Delete category
        async function deleteCategory(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the category. Subcategories and products will not be deleted but will lose parent association.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.delete(`/categories/${id}`);

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        // Refresh all data from APIs
                        await Promise.all([
                            loadCategoriesData(),
                            loadStatistics(),
                            loadParentCategories()
                        ]);
                    } else {
                        toastr.error(response.data.message || 'Failed to delete category');
                    }
                } catch (error) {
                    console.error('Error deleting category:', error);
                    toastr.error(error.response?.data?.message || 'Failed to delete category');
                }
            }
        }

        // Toggle category status
        async function toggleCategoryStatus(id, isActive) {
            const result = await Swal.fire({
                title: 'Confirm Status Change',
                text: `Are you sure you want to ${isActive ? 'activate' : 'deactivate'} this category?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${isActive ? 'activate' : 'deactivate'}`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: isActive ? '#10b981' : '#ef4444'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(`/categories/${id}/status`, {
                        status: isActive ? 1 : 0
                    });

                    if (response.data.success) {
                        toastr.success(`Category ${isActive ? 'activated' : 'deactivated'} successfully!`);

                        // Refresh all data from APIs
                        await Promise.all([
                            loadCategoriesData(),
                            loadStatistics()
                        ]);
                    } else {
                        toastr.error('Failed to update category status');
                        // Revert the switch by reloading data
                        loadCategoriesData();
                    }
                } catch (error) {
                    console.error('Error updating status:', error);
                    toastr.error(error.response?.data?.message || 'Failed to update category status');
                    // Revert the switch by reloading data
                    loadCategoriesData();
                }
            } else {
                // Revert the switch if cancelled
                loadCategoriesData();
            }
        }

        // Apply bulk action
        async function applyBulkAction(action) {
            const selectedRows = categoriesTable.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                toastr.warning('No categories selected');
                return;
            }

            let endpoint, payload, message;

            switch (action) {
                case 'activate':
                    endpoint = '/categories/bulk-status';
                    payload = {
                        ids: selectedIds,
                        status: 1
                    };
                    message = 'Activate';
                    break;
                case 'deactivate':
                    endpoint = '/categories/bulk-status';
                    payload = {
                        ids: selectedIds,
                        status: 0
                    };
                    message = 'Deactivate';
                    break;
                case 'delete':
                    endpoint = '/categories/bulk-delete';
                    payload = {
                        ids: selectedIds
                    };
                    message = 'Delete';
                    break;
                default:
                    return;
            }

            const actionText = action === 'delete' ? 'delete' : 'update';

            const result = await Swal.fire({
                title: 'Confirm Bulk Action',
                text: `Are you sure you want to ${message.toLowerCase()} ${selectedIds.length} categor${selectedIds.length > 1 ? 'ies' : 'y'}?`,
                icon: action === 'delete' ? 'warning' : 'info',
                showCancelButton: true,
                confirmButtonText: `Yes, ${message}`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: action === 'delete' ? '#ef4444' : '#3b82f6'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(endpoint, payload);

                    if (response.data.success) {
                        toastr.success(response.data.message);
                        closeBulkActions();

                        // Refresh all data from APIs
                        await Promise.all([
                            loadCategoriesData(),
                            loadStatistics(),
                            loadParentCategories()
                        ]);
                    }
                } catch (error) {
                    console.error('Error in bulk action:', error);
                    toastr.error(error.response?.data?.message || `Failed to ${actionText} categories`);
                }
            }
        }

        // Refresh all data
        async function refreshAll() {
            try {
                await Promise.all([
                    loadCategoriesData(),
                    loadStatistics(),
                    loadParentCategories()
                ]);
                toastr.info('Data refreshed');
            } catch (error) {
                console.error('Error refreshing data:', error);
                toastr.error('Failed to refresh data');
            }
        }
    </script>
@endpush
