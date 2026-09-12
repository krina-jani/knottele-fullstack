{{-- resources/views/admin/media/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Media Library')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-stone-800 mb-2">Media Library</h2>
                <p class="text-stone-500 font-medium">Upload and manage your media files</p>
            </div>
            <div class="flex space-x-3">
                <button id="topUploadBtn" class="btn-primary">
                    <i class="fas fa-upload mr-2 text-xs"></i>Upload Files
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 mb-8">
        <div class="flex flex-col items-center justify-center border-2 border-dashed border-stone-200 rounded-3xl p-12 hover:border-red-400 hover:bg-red-50/30 transition-all duration-300 group cursor-pointer"
            id="dropzone" onclick="document.getElementById('fileInput').click()">
            <div class="w-20 h-20 bg-red-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-cloud-upload-alt text-4xl text-red-600"></i>
            </div>
            <p class="text-xl font-bold text-stone-800 mb-2">Drag & drop files here</p>
            <p class="text-stone-500 mb-6 font-medium">or click to browse your computer</p>
            <div class="flex space-x-2 items-center text-sm text-stone-400 font-bold uppercase tracking-widest">
                <span>JPG</span>
                <span class="w-1 h-1 bg-stone-300 rounded-full"></span>
                <span>PNG</span>
                <span class="w-1 h-1 bg-stone-300 rounded-full"></span>
                <span>WEBP</span>
                <span class="w-1 h-1 bg-stone-300 rounded-full"></span>
                <span>SVG</span>
            </div>
            <input type="file" id="fileInput" multiple class="hidden" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
        </div>

        <!-- Upload Progress -->
        <div id="uploadProgress" class="hidden mt-8 max-w-2xl mx-auto">
            <div class="bg-stone-50 rounded-2xl p-6 border border-stone-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin w-5 h-5 border-2 border-red-500 border-t-transparent rounded-full"></div>
                        <span class="text-sm font-bold text-stone-800 uppercase tracking-wider">Uploading...</span>
                    </div>
                    <span id="uploadCount" class="text-sm font-bold text-red-600">0/0</span>
                </div>
                <div class="w-full bg-stone-200 rounded-full h-3 overflow-hidden">
                    <div id="uploadBar" class="bg-gradient-to-r from-red-400 to-red-600 h-full rounded-full transition-all duration-300 shadow-[0_0_10px_rgba(14,165,233,0.3)]" style="width: 0%">
                    </div>
                </div>
                <div id="uploadList" class="mt-4 space-y-2 max-h-40 overflow-y-auto custom-scrollbar"></div>
            </div>
        </div>

        <!-- Bulk Alt Text (Optional) -->
        <div id="bulkAltContainer" class="hidden mt-6 max-w-2xl mx-auto">
            <label class="block text-sm font-semibold text-stone-700 mb-2">Alternative Text for All Files</label>
            <div class="flex space-x-2">
                <input type="text" id="bulkAltText" placeholder="Describe all images (optional)"
                    class="flex-1 rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all font-medium">
                <button onclick="clearBulkAltText()" class="btn-secondary px-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-[10px] text-stone-400 mt-2 uppercase tracking-wider font-bold">This text will be applied to all uploaded files</p>
        </div>
    </div>

    <!-- Media Library Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-stone-100">
            <h3 class="text-xl font-bold text-stone-800">Media Files</h3>
        </div>
        <div class="p-8">
            <!-- Loading State -->
            <div id="loadingState" class="hidden text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                <p class="mt-2 text-stone-500">Loading media files...</p>
            </div>

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <div class="order-2 sm:order-1">
                    <div class="relative" style="width: 260px;">
                        <input type="text" id="searchInput" placeholder="Search media..."
                            class="pl-10 pr-4 py-2 rounded-lg border border-stone-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-stone-400"></i>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <button id="sortBtn" class="btn-secondary">
                            <i class="fas fa-sort mr-2 text-xs"></i>Sort
                        </button>
                        <div id="sortMenu"
                            class="absolute mt-2 w-48 bg-white rounded-xl shadow-xl border border-stone-100 py-2 z-50 hidden right-0">
                            <button data-sort="created_at_desc"
                                class="sort-option w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-check mr-2 text-red-600"></i>Newest First
                            </button>
                            <button data-sort="created_at_asc"
                                class="sort-option w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-check mr-2 opacity-0"></i>Oldest First
                            </button>
                            <button data-sort="name_asc"
                                class="sort-option w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-check mr-2 opacity-0"></i>Name A → Z
                            </button>
                            <button data-sort="name_desc"
                                class="sort-option w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-check mr-2 opacity-0"></i>Name Z → A
                            </button>
                            <button data-sort="size_desc"
                                class="sort-option w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-check mr-2 opacity-0"></i>Size: Large → Small
                            </button>
                            <button data-sort="size_asc"
                                class="sort-option w-full text-left px-4 py-2 text-stone-600 hover:bg-red-50 hover:text-red-600 text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-check mr-2 opacity-0"></i>Size: Small → Large
                            </button>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <button id="bulkDeleteBtn" class="btn-secondary text-rose-600 border-rose-100 bg-rose-50 hover:bg-rose-100 hidden">
                        <i class="fas fa-trash mr-2 text-xs"></i>Delete Selected
                    </button>

                    <!-- Refresh -->
                    <button onclick="refreshData()" class="btn-secondary">
                        <i class="fas fa-redo mr-2 text-xs"></i>Refresh
                    </button>
                </div>
            </div>

            <!-- Tabulator Table -->
            <div id="mediaTable"></div>

            <!-- Custom Pagination -->
            <div id="customPagination" class="mt-8 flex items-center justify-between border-t border-stone-100 pt-6">
                <div class="text-sm font-bold text-stone-400 uppercase tracking-wider" id="paginationInfo">
                    Showing 0 to 0 of 0 entries
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex bg-stone-50 rounded-xl p-1 border border-stone-200">
                        <button onclick="changePage(1)" id="firstPageBtn"
                            class="w-10 h-10 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-600 hover:bg-white transition-all disabled:opacity-30 disabled:pointer-events-none">
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button onclick="changePage(currentPage - 1)" id="prevPageBtn"
                            class="w-10 h-10 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-600 hover:bg-white transition-all disabled:opacity-30 disabled:pointer-events-none">
                            <i class="fas fa-angle-left"></i>
                        </button>
                        <div id="pageNumbers" class="flex space-x-1 px-1">
                            <!-- Page numbers will be inserted here -->
                        </div>
                        <button onclick="changePage(currentPage + 1)" id="nextPageBtn"
                            class="w-10 h-10 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-600 hover:bg-white transition-all disabled:opacity-30 disabled:pointer-events-none">
                            <i class="fas fa-angle-right"></i>
                        </button>
                        <button onclick="changePage(totalPages)" id="lastPageBtn"
                            class="w-10 h-10 flex items-center justify-center rounded-lg text-stone-400 hover:text-red-600 hover:bg-white transition-all disabled:opacity-30 disabled:pointer-events-none">
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </div>

                    <select id="pageSizeSelect" class="bg-stone-50 border border-stone-200 rounded-xl px-4 py-2 text-sm font-bold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                        <option value="10">10 / Page</option>
                        <option value="25">25 / Page</option>
                        <option value="50">50 / Page</option>
                        <option value="100">100 / Page</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-semibold text-lg" id="previewTitle">Preview</h3>
                <button onclick="hidePreview()" class="text-stone-500 hover:text-stone-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 overflow-auto max-h-[calc(90vh-100px)]">
                <div class="text-center">
                    <img id="previewImage" src="" alt=""
                        class="max-w-full max-h-[70vh] mx-auto rounded-lg hidden">
                    <div id="previewNonImage" class="hidden">
                        <div class="w-48 h-48 bg-stone-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file text-stone-400 text-6xl"></i>
                        </div>
                        <div class="text-left max-w-md mx-auto">
                            <p><strong>File Name:</strong> <span id="previewFileName"></span></p>
                            <p><strong>Type:</strong> <span id="previewFileType"></span></p>
                            <p><strong>Size:</strong> <span id="previewFileSize"></span></p>
                            <p><strong>Uploaded:</strong> <span id="previewFileDate"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Base Tabulator container */
        #mediaTable {
            border: none !important;
            background: transparent !important;
            min-height: 400px;
        }

        /* Table holder - makes it fill available space */
        .tabulator-tableholder {
            background: transparent !important;
            border: none !important;
        }

        /* Header styling */
        .tabulator .tabulator-header {
            border: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            background-color: #fafaf9 !important;
            font-weight: 600;
            color: #1c1917;
        }

        .tabulator .tabulator-col {
            background-color: #fafaf9 !important;
            border-right: 1px solid #f1f5f9 !important;
            padding: 12px 8px !important;
        }

        .tabulator .tabulator-col:last-child {
            border-right: none !important;
        }

        /* Row styling */
        .tabulator-row {
            border-bottom: 1px solid #fafaf9 !important;
            transition: background-color 0.2s ease;
        }

        .tabulator-row.tabulator-selectable:hover {
            background-color: #f0f9ff !important;
        }

        .tabulator-row.tabulator-selected {
            background-color: #e0f2fe !important;
        }

        /* Cell styling */
        .tabulator-cell {
            padding: 12px 8px !important;
            border-right: 1px solid #fafaf9 !important;
            vertical-align: middle !important;
        }

        .tabulator-cell:last-child {
            border-right: none !important;
        }

        /* Remove default Tabulator borders */
        .tabulator,
        .tabulator-table,
        .tabulator-header-contents,
        .tabulator-headers {
            border: none !important;
        }

        /* Hide Tabulator's default pagination */
        .tabulator-footer {
            display: none !important;
        }

        /* Custom pagination styling */
        #customPagination {
            padding: 16px 0;
            border-top: 1px solid #f1f5f9;
        }

        .page-number {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e7e5e4;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .page-number:hover {
            background-color: #f5f5f4;
            border-color: #a8a29e;
        }

        .page-number.active {
            background-color: #0ea5e9;
            color: white;
            border-color: #0ea5e9;
        }

        .page-number.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Loading state */
        #loadingState {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Global variables for pagination
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10;
        let totalItems = 0;
        let mediaTable = null;
        let currentSort = 'created_at_desc';
        let currentSearch = '';

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Media module initialized');

            // Initialize Tabulator table
            initializeMediaTable();

            // Load initial data
            loadMediaData();

            // Setup event listeners
            setupEventListeners();

            // Load statistics (optional)
            // loadStatistics();
        });

        // Setup event listeners
        function setupEventListeners() {
            // File upload
            const fileInput = document.getElementById('fileInput');
            const dropzone = document.getElementById('dropzone');

            fileInput.addEventListener('change', handleFileSelect);

            // Drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropzone.classList.add('border-red-400', 'bg-red-50');
            }

            function unhighlight(e) {
                dropzone.classList.remove('border-red-400', 'bg-red-50');
            }

            dropzone.addEventListener('drop', handleDrop, false);

            // Bulk actions
            document.getElementById('bulkDeleteBtn').addEventListener('click', confirmBulkDelete);

            // Search with debounce
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value;
                    currentPage = 1;
                    loadMediaData();
                }, 500);
            });

            // Sort options
            document.querySelectorAll('.sort-option').forEach(option => {
                option.addEventListener('click', function() {
                    currentSort = this.getAttribute('data-sort');
                    currentPage = 1;

                    // Update sort menu UI
                    document.querySelectorAll('.sort-option').forEach(opt => {
                        const icon = opt.querySelector('.fas.fa-check');
                        if (opt.getAttribute('data-sort') === currentSort) {
                            icon.classList.remove('opacity-0');
                            icon.classList.add('text-red-600');
                        } else {
                            icon.classList.add('opacity-0');
                            icon.classList.remove('text-red-600');
                        }
                    });

                    // Hide sort menu
                    document.getElementById('sortMenu').classList.add('hidden');

                    // Load data with new sort
                    loadMediaData();
                });
            });

            // Toggle sort menu
            document.getElementById('sortBtn').addEventListener('click', function(e) {
                e.stopPropagation();
                document.getElementById('sortMenu').classList.toggle('hidden');
            });

            // Page size change
            document.getElementById('pageSizeSelect').addEventListener('change', function() {
                perPage = parseInt(this.value);
                currentPage = 1;
                loadMediaData();
            });

            // Top upload button
            document.getElementById('topUploadBtn').addEventListener('click', () => {
                fileInput.click();
            });

            // Close modals on outside click
            document.addEventListener('click', function(e) {
                const previewModal = document.getElementById('previewModal');
                if (!previewModal.classList.contains('hidden') && e.target === previewModal) {
                    hidePreview();
                }

                // Close sort menu on outside click
                const sortMenu = document.getElementById('sortMenu');
                const sortBtn = document.getElementById('sortBtn');
                if (!sortMenu.classList.contains('hidden') &&
                    !sortMenu.contains(e.target) &&
                    !sortBtn.contains(e.target)) {
                    sortMenu.classList.add('hidden');
                }
            });

            // Close modals on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const previewModal = document.getElementById('previewModal');
                    if (!previewModal.classList.contains('hidden')) {
                        hidePreview();
                    }
                }
            });
        }

        // Initialize Tabulator table
        function initializeMediaTable() {
            mediaTable = new Tabulator("#mediaTable", {
                data: [],
                layout: "fitColumns",
                maxHeight: "60vh",
                responsiveLayout: "hide",
                selectable: 1,
                columns: [{
                        title: "<input type='checkbox' id='selectAllMedia'>",
                        field: "id",
                        formatter: "rowSelection",
                        titleFormatter: "rowSelection",
                        hozAlign: "center",
                        headerSort: false,
                        width: 50,
                        cssClass: "select-checkbox"
                    },
                    {
                        title: "Preview",
                        field: "thumbnail_url",
                        width: 80,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const data = cell.getRow().getData();
                            return `
                                <div class="w-12 h-12 mx-auto overflow-hidden rounded-lg bg-stone-100 flex items-center justify-center cursor-pointer hover:opacity-90" onclick="previewMedia(${data.id})">
                                    ${data.mime_type.startsWith('image/') ?
                                        `<img src="${data.thumbnail_url}" alt="${data.alt_text || data.file_name}" class="w-full h-full object-cover">` :
                                        `<i class="fas fa-file text-stone-400 text-xl"></i>`
                                    }
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Name",
                        field: "file_name",
                        widthGrow: 2,
                        formatter: function(cell) {
                            const data = cell.getRow().getData();
                            return `
                                <div class="flex flex-col">
                                    <span class="font-medium text-stone-900 truncate" title="${data.file_name}">${data.file_name}</span>
                                    ${data.alt_text ?
                                        `<span class="text-xs text-stone-500 truncate" title="${data.alt_text}">${data.alt_text}</span>` :
                                        ''
                                    }
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Type",
                        field: "mime_type",
                        width: 120,
                        formatter: function(cell) {
                            const type = cell.getValue();
                            const icon = type.startsWith('image/') ? 'fa-image' : 'fa-file';
                            const color = type.startsWith('image/') ? 'text-red-500' : 'text-stone-500';
                            return `
                                <div class="flex items-center space-x-2">
                                    <i class="fas ${icon} ${color}"></i>
                                    <span>${type.split('/')[1] || type}</span>
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Size",
                        field: "size_formatted",
                        width: 100,
                        hozAlign: "right",
                        sorter: "number"
                    },
                    {
                        title: "Uploaded",
                        field: "created_at_formatted",
                        width: 150,
                        hozAlign: "center"
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 120,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const id = cell.getValue();
                            return `
                                <div class="flex space-x-2 justify-center">
                                   

                                    <button onclick="deleteMedia(${id})"
                                            class="p-1 text-rose-600 hover:text-rose-900 tooltip"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                rowFormatter: function(row) {
                    const rowEl = row.getElement();
                    rowEl.classList.add('hover:bg-red-50');
                },
                rowSelectionChanged: function(data, rows) {
                    updateBulkActions(data.length);
                }
            });

            // Select all checkbox
            $(document).on('click', '#selectAllMedia', function() {
                if ($(this).is(':checked')) {
                    mediaTable.selectRow();
                } else {
                    mediaTable.deselectRow();
                }
            });
        }

        // Load media data
        async function loadMediaData() {
            try {
                showLoading(true);

                const [sortBy, sortDir = 'desc'] = currentSort.split('_').length > 2 ? [currentSort.split('_').slice(0,
                        -1).join('_'), currentSort.split('_').pop()] :
                    currentSort.split('_');

                const params = new URLSearchParams({
                    page: currentPage,
                    per_page: perPage,
                    sort_by: sortBy,
                    sort_dir: sortDir,
                    search: currentSearch
                });

                const response = await axios.get(`/api/admin/media?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
                    }
                });

                if (response.data.success) {
                    const data = response.data.data;
                    
                    // Update table data
                    mediaTable.setData(data.data || []);

                    // Update pagination info (Laravel standard pagination properties)
                    totalItems = data.total || 0;
                    totalPages = data.last_page || 1;
                    
                    updatePaginationInfo(data);
                    renderPageNumbers();

                    // Update bulk actions
                    updateBulkActions(0);
                }
            } catch (error) {
                console.error('Error loading media:', error);
                toastr.error('Failed to load media files');
            } finally {
                showLoading(false);
            }
        }

        // Update pagination info
        function updatePaginationInfo(meta) {
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo && meta) {
                paginationInfo.innerHTML = `
                    Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total || 0} entries
                `;
            }

            // Update pagination button states
            updatePaginationButtons();
        }

        // Update pagination buttons
        function updatePaginationButtons() {
            const firstPageBtn = document.getElementById('firstPageBtn');
            const prevPageBtn = document.getElementById('prevPageBtn');
            const nextPageBtn = document.getElementById('nextPageBtn');
            const lastPageBtn = document.getElementById('lastPageBtn');

            // Disable buttons when appropriate
            firstPageBtn.disabled = currentPage === 1;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages;
            lastPageBtn.disabled = currentPage === totalPages;

            // Update page size selector
            document.getElementById('pageSizeSelect').value = perPage;
        }

        // Render page numbers
        function renderPageNumbers() {
            const pageNumbersDiv = document.getElementById('pageNumbers');
            pageNumbersDiv.innerHTML = '';

            // Always show first page
            addPageButton(1, pageNumbersDiv);

            // Calculate range of pages to show
            let startPage = Math.max(2, currentPage - 2);
            let endPage = Math.min(totalPages - 1, currentPage + 2);

            // Adjust if we're near the start
            if (currentPage <= 3) {
                endPage = Math.min(totalPages - 1, 5);
            }

            // Adjust if we're near the end
            if (currentPage >= totalPages - 2) {
                startPage = Math.max(2, totalPages - 4);
            }

            // Add ellipsis after first page if needed
            if (startPage > 2) {
                addEllipsis(pageNumbersDiv);
            }

            // Add middle pages
            for (let i = startPage; i <= endPage; i++) {
                addPageButton(i, pageNumbersDiv);
            }

            // Add ellipsis before last page if needed
            if (endPage < totalPages - 1) {
                addEllipsis(pageNumbersDiv);
            }

            // Always show last page if there is more than one page
            if (totalPages > 1) {
                addPageButton(totalPages, pageNumbersDiv);
            }
        }

        function addPageButton(pageNum, container) {
            const button = document.createElement('button');
            button.className = `page-number ${pageNum === currentPage ? 'active' : ''}`;
            button.textContent = pageNum;
            button.onclick = () => changePage(pageNum);

            // Disable if it's the current page
            if (pageNum === currentPage) {
                button.disabled = true;
                button.classList.add('disabled');
            }

            container.appendChild(button);
        }

        function addEllipsis(container) {
            const span = document.createElement('span');
            span.className = 'page-number disabled';
            span.textContent = '...';
            span.style.cursor = 'default';
            container.appendChild(span);
        }

        // Change page
        function changePage(page) {
            if (page < 1 || page > totalPages || page === currentPage) {
                return;
            }

            currentPage = page;
            loadMediaData();

            // Scroll to top of table
            const tableContainer = document.querySelector('#mediaTable .tabulator-tableholder');
            if (tableContainer) {
                tableContainer.scrollTop = 0;
            }
        }

        // Show/hide loading
        function showLoading(show) {
            const loadingState = document.getElementById('loadingState');
            if (show) {
                loadingState.classList.remove('hidden');
                loadingState.classList.add('flex', 'flex-col', 'items-center');
            } else {
                loadingState.classList.add('hidden');
                loadingState.classList.remove('flex', 'flex-col', 'items-center');
            }
        }

        // Update bulk actions
        function updateBulkActions(selectedCount) {
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            if (selectedCount > 0) {
                bulkDeleteBtn.classList.remove('hidden');
                bulkDeleteBtn.innerHTML = `<i class="fas fa-trash mr-2"></i>Delete (${selectedCount})`;
            } else {
                bulkDeleteBtn.classList.add('hidden');
            }
        }

        // Refresh data
        function refreshData() {
            currentPage = 1;
            loadMediaData();
            toastr.info('Data refreshed');
        }

        // The rest of your functions (handleFileSelect, uploadFiles, editMedia, deleteMedia, etc.)
        // ... [Keep all your existing functions for file handling, uploads, etc.] ...

        // Handle file selection
        function handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                // Show bulk alt text container for multiple files
                if (files.length > 1) {
                    document.getElementById('bulkAltContainer').classList.remove('hidden');
                }
                uploadFiles(files);
            }
            // Reset input
            e.target.value = '';
        }

        // Handle drop
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                uploadFiles(files);
            }
        }

        // Upload files (simplified version)
        async function uploadFiles(files) {
            // Show upload progress
            showUploadProgress(files.length);

            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            // Add alt text if available
            const altText = document.getElementById('bulkAltText')?.value || '';
            if (altText) {
                formData.append('alt_text', altText);
            }

            try {
                const response = await axios.post('/api/admin/media/upload', formData, {

                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                    },
                    onUploadProgress: function(progressEvent) {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent
                            .total);
                        updateUploadProgress(percentCompleted, progressEvent.loaded, progressEvent.total);
                    }
                });

                if (response.data.success) {
                    toastr.success(`Successfully uploaded ${response.data.data.total_uploaded} file(s)`);

                    // Refresh data
                    refreshData();

                    // Clear bulk alt text
                    document.getElementById('bulkAltText').value = '';
                    document.getElementById('bulkAltContainer').classList.add('hidden');
                }
            } catch (error) {
                console.error('Upload error:', error);
                toastr.error('Upload failed');
            } finally {
                hideUploadProgress();
            }
        }

        // Show upload progress UI
        function showUploadProgress(totalFiles) {
            const progressDiv = document.getElementById('uploadProgress');
            const uploadList = document.getElementById('uploadList');

            progressDiv.classList.remove('hidden');
            uploadList.innerHTML = '';
            document.getElementById('uploadCount').textContent = `0/${totalFiles}`;
            document.getElementById('uploadBar').style.width = '0%';
        }

        function updateUploadProgress(percent, loaded, total) {
            document.getElementById('uploadBar').style.width = `${percent}%`;
            const loadedFormatted = formatBytes(loaded);
            const totalFormatted = formatBytes(total);
            document.getElementById('uploadCount').textContent = `${loadedFormatted}/${totalFormatted}`;
        }

        function hideUploadProgress() {
            setTimeout(() => {
                document.getElementById('uploadProgress').classList.add('hidden');
            }, 1000);
        }

        // Edit media
        async function editMedia(id) {
            try {
                // First get media details
                const response = await axios.get(`/api/admin/media/${id}`, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                    },
                });

                if (response.data.success) {
                    const media = response.data.data;

                    Swal.fire({
                        title: 'Edit Media',
                        html: `
                            <div class="text-left">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-stone-700 mb-1">File Name</label>
                                    <input type="text" id="editFileName" class="swal2-input" value="${media.file_name}" readonly>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-stone-700 mb-1">Alternative Text</label>
                                    <input type="text" id="editAltText" class="swal2-input" value="${media.alt_text || ''}" placeholder="Describe this image...">
                                </div>
                                ${media.url ? `
                                                            <div class="mb-4 text-center">
                                                                <img src="${media.url}" alt="${media.alt_text || media.file_name}" class="max-w-full max-h-48 mx-auto rounded-lg">
                                                            </div>
                                                        ` : ''}
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Update',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            const altText = document.getElementById('editAltText').value;
                            return {
                                alt_text: altText.trim()
                            };
                        }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const updateResponse = await axios.put(`/api/admin/media/${id}`, result
                                    .value, {
                                        headers: {
                                            'Content-Type': 'multipart/form-data',
                                            'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                                        },
                                    });

                                if (updateResponse.data.success) {
                                    toastr.success(updateResponse.data.message);
                                    refreshData();
                                }
                            } catch (error) {
                                toastr.error('Failed to update media');
                            }
                        }
                    });
                }
            } catch (error) {
                toastr.error('Failed to load media details');
            }
        }

        // Delete media
        async function deleteMedia(id) {
            const result = await Swal.fire({
                title: 'Delete Media?',
                text: "This will permanently delete the media file. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.delete(`/api/admin/media/${id}`, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                        }
                    });

                    if (response.data.success) {
                        toastr.success(response.data.message);
                        refreshData();
                    } else {
                        toastr.error(response.data.message || 'Failed to delete media');
                    }
                } catch (error) {
                    if (error.response?.status === 400) {
                        toastr.error(error.response.data.message || 'Cannot delete media in use');
                    } else {
                        toastr.error('Failed to delete media');
                    }
                }
            }
        }

        // Confirm bulk delete
        async function confirmBulkDelete() {
            const selectedRows = mediaTable.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                toastr.warning('Please select media files to delete');
                return;
            }

            const result = await Swal.fire({
                title: 'Delete Selected Media?',
                html: `You are about to delete <strong>${selectedIds.length}</strong> media file(s).<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Yes, delete ${selectedIds.length} file(s)`,
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.post('/api/admin/media/bulk-delete', {
                        ids: selectedIds
                    }, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                        }
                    });

                    if (response.data.success) {
                        const deletedCount = response.data.data.deleted_count;
                        toastr.success(`Successfully deleted ${deletedCount} file(s)`);
                        refreshData();
                    }
                } catch (error) {
                    toastr.error('Failed to delete media files');
                }
            }
        }

        // Utility functions
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function clearBulkAltText() {
            document.getElementById('bulkAltText').value = '';
        }
    </script>
@endpush
