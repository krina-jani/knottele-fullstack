{{-- resources/views/admin/media/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Knotelle Media Manager')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-stone-800">Website Media Manager</h2>
                    <span class="px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-full border border-red-200 uppercase tracking-wider">
                        Structured Taxonomy
                    </span>
                </div>
                <p class="text-stone-500 font-medium mt-1">Manage, organize, and replace website visual assets by Page, Section, Slot, and Device.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="openGenericUploadModal()" class="btn-primary flex items-center gap-2">
                    <i class="fas fa-cloud-upload-alt text-sm"></i>
                    <span>Upload Media</span>
                </button>
                <button onclick="loadManagerData()" class="btn-secondary flex items-center gap-2">
                    <i class="fas fa-sync-alt text-xs"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & View Controls Toolbar -->
    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Filters Group -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                <!-- Page Filter -->
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Page</label>
                    <select id="filterPage" onchange="applyFilters()" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="all">All Pages</option>
                        <option value="homepage" selected>Homepage</option>
                        <option value="shop">Shop Page</option>
                        <option value="custom_order">Custom Order Page</option>
                        <option value="about">About Page</option>
                        <option value="contact">Contact Page</option>
                        <option value="global">Global Site Assets</option>
                    </select>
                </div>

                <!-- Section Filter -->
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Section</label>
                    <select id="filterSection" onchange="applyFilters()" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="all">All Sections</option>
                        <option value="hero">Hero Banner (Homepage)</option>
                        <option value="categories">Shop by Category (Homepage)</option>
                        <option value="custom_crochet">Custom Crochet Banner (Homepage)</option>
                        <option value="brand_story">Brand Story (Homepage)</option>
                        <option value="bestsellers">Best Sellers (Homepage)</option>
                        <option value="trust_benefits">Trust & Benefits (Homepage)</option>
                        <option value="custom_order">Custom Order CTA (Homepage)</option>
                        <option value="newsletter">Newsletter (Homepage)</option>
                        <option value="blog_reels">Blog / Videos & Reels (Homepage)</option>
                        <option value="footer">Footer Artwork (Homepage)</option>
                        <option value="shop_banner">Shop Header & Banner (Shop)</option>
                        <option value="shop_promo">Shop Promo Banner (Shop)</option>
                        <option value="custom_order_header">Custom Order Header Banner (Custom Order)</option>
                        <option value="custom_order_showcase">Craft Process Showcase (Custom Order)</option>
                        <option value="about_story">Atelier & Story (About)</option>
                        <option value="contact_header">Contact Banner & Atelier (Contact)</option>
                        <option value="global_assets">Global Site Assets</option>
                    </select>
                </div>

                <!-- Device Filter -->
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1.5">Device Target</label>
                    <select id="filterDevice" onchange="applyFilters()" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="all">All Devices</option>
                        <option value="desktop">Desktop Only</option>
                        <option value="mobile">Mobile Only</option>
                    </select>
                </div>
            </div>

            <!-- View Switcher -->
            <div class="flex items-center gap-2 pt-2 lg:pt-5 shrink-0">
                <div class="bg-stone-100 p-1 rounded-2xl flex items-center border border-stone-200">
                    <button id="viewSectionsBtn" onclick="switchView('sections')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white text-red-700 shadow-xs flex items-center gap-1.5">
                        <i class="fas fa-th-large"></i>
                        <span>Section Manager</span>
                    </button>
                    <button id="viewLibraryBtn" onclick="switchView('library')" class="px-4 py-2 rounded-xl text-xs font-bold transition-all text-stone-500 hover:text-stone-800 flex items-center gap-1.5">
                        <i class="fas fa-images"></i>
                        <span>All Media Files</span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- MAIN VIEW CONTAINER 1: STRUCTURED SECTION CARDS -->
    <div id="sectionsViewContainer" class="space-y-8">
        
        <!-- Loading Spinner -->
        <div id="managerLoading" class="bg-white rounded-3xl p-16 text-center border border-stone-100 shadow-sm">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-3 border-red-600 border-t-transparent mb-4"></div>
            <p class="text-stone-700 font-bold text-base">Loading Knotelle Media Manager...</p>
            <p class="text-stone-400 text-xs mt-1">Fetching structured slots and website assets</p>
        </div>

        <!-- Rendered Sections Content will be inserted dynamically here -->
        <div id="sectionsList" class="space-y-8 hidden"></div>

    </div>

    <!-- MAIN VIEW CONTAINER 2: ALL MEDIA LIBRARY TABLE & UPLOAD -->
    <div id="libraryViewContainer" class="hidden space-y-8">
        
        <!-- Drag & Drop Upload Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8">
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-stone-200 rounded-3xl p-10 hover:border-red-400 hover:bg-red-50/30 transition-all duration-300 group cursor-pointer"
                id="libraryDropzone" onclick="document.getElementById('libraryFileInput').click()">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-cloud-upload-alt text-3xl text-red-600"></i>
                </div>
                <p class="text-lg font-bold text-stone-800 mb-1">Drag & drop files here</p>
                <p class="text-stone-500 mb-4 text-xs font-medium">or click to browse your desktop / mobile gallery</p>
                <div class="flex space-x-2 items-center text-xs text-stone-400 font-bold uppercase tracking-wider">
                    <span>JPG</span> • <span>PNG</span> • <span>WEBP</span> • <span>SVG</span>
                </div>
                <input type="file" id="libraryFileInput" multiple class="hidden" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
            </div>
        </div>

        <!-- Tabulator Table Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <div class="relative w-72">
                    <input type="text" id="librarySearchInput" placeholder="Search media by name/slot..."
                        class="pl-10 pr-4 py-2.5 rounded-xl border border-stone-200 bg-stone-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500 w-full text-sm">
                    <i class="fas fa-search absolute left-3.5 top-3.5 text-stone-400"></i>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="refreshLibraryData()" class="btn-secondary text-xs py-2 px-3">
                        <i class="fas fa-sync-alt mr-1"></i>Refresh Table
                    </button>
                </div>
            </div>
            <div id="mediaTable"></div>
        </div>

    </div>

    <!-- MODAL 1: DIRECT SLOT REPLACE / UPLOAD MODAL -->
    <div id="slotUploadModal" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn">
            
            <!-- Sticky / Pinned Header -->
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-stone-800" id="slotModalTitle">Replace Image</h3>
                    <p class="text-xs text-stone-500 font-medium" id="slotModalSubtitle">Assign a new image to this website slot</p>
                </div>
                <button type="button" onclick="closeSlotUploadModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Wrapper -->
            <form id="slotUploadForm" onsubmit="handleSlotUploadSubmit(event)" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                <input type="hidden" id="targetPage" name="page">
                <input type="hidden" id="targetSection" name="section">
                <input type="hidden" id="targetSlot" name="slot">
                <input type="hidden" id="targetDevice" name="device">
                <input type="hidden" id="selectedMediaId" name="media_id">

                <!-- Scrollable Body Content -->
                <div class="p-6 space-y-4 overflow-y-auto flex-1 overscroll-contain">
                    <!-- Recommended Dimensions Info Box -->
                    <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-3.5 flex items-center gap-3 text-amber-800">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                            <i class="fas fa-ruler-combined text-amber-700"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold block uppercase tracking-wider">Recommended Aspect Ratio & Dimensions</span>
                            <span class="text-sm font-bold" id="slotModalDimensions">1920 × 700 px</span>
                        </div>
                    </div>

                    <!-- Dropzone / File Selector -->
                    <div class="border-2 border-dashed border-stone-200 rounded-2xl p-5 text-center hover:border-red-400 hover:bg-red-50/20 transition-all cursor-pointer"
                         onclick="document.getElementById('slotFileInput').click()">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mx-auto mb-2 text-red-600">
                            <i class="fas fa-image text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-stone-800" id="slotFileLabel">Choose a file from Phone / Desktop</p>
                        <p class="text-xs text-stone-400 mt-0.5">Supports JPG, PNG, WEBP (Max 20MB)</p>
                        <input type="file" id="slotFileInput" name="file" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" onchange="handleSlotFileChange(this)">
                    </div>

                    <!-- Live Preview of chosen file -->
                    <div id="slotFilePreviewContainer" class="hidden bg-stone-50 rounded-2xl p-3 border border-stone-200 flex items-center gap-3">
                        <img id="slotFilePreviewImg" src="" class="w-16 h-16 rounded-xl object-cover border border-stone-200">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-stone-800 truncate" id="slotFileName"></p>
                            <p class="text-[11px] text-stone-500" id="slotFileSize"></p>
                        </div>
                        <button type="button" onclick="clearSlotFileInput()" class="text-stone-400 hover:text-red-600 p-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Metadata Inputs -->
                    <div class="space-y-3 pt-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Title / Headline</label>
                                <input type="text" id="slotTitleInput" name="title" placeholder="e.g. Custom Crochet" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Subtitle / Highlight</label>
                                <input type="text" id="slotSubtitleInput" name="subtitle" placeholder="e.g. Just for You" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Description Paragraph</label>
                            <textarea id="slotDescInput" name="description" rows="2" placeholder="e.g. Your imagination, our yarn. Let's create something special together." class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Button Label (CTA)</label>
                                <input type="text" id="slotCtaTextInput" name="cta_text" placeholder="e.g. Request Your Custom Order" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Button Link URL</label>
                                <input type="text" id="slotCtaLinkInput" name="cta_link" placeholder="e.g. /custom-order" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Hanging Tag / Badge Text</label>
                                <input type="text" id="slotTagTextInput" name="tag_text" placeholder="e.g. Turn Your Ideas Into Handmade Reality" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Alt Text (SEO & Accessibility)</label>
                                <input type="text" id="slotAltInput" name="alt_text" placeholder="e.g. Handmade crochet creations boutique" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sticky Footer with Action Buttons -->
                <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 shrink-0 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeSlotUploadModal()" class="btn-secondary text-xs px-4 py-2.5">
                        Cancel
                    </button>
                    <button type="submit" id="slotUploadSubmitBtn" class="btn-primary text-xs px-6 py-2.5 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Save & Apply to Website</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL 2: RESPONSIVE DEVICE PREVIEW MODAL -->
    <div id="devicePreviewModal" class="fixed inset-0 bg-stone-900/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100">
            
            <!-- Header with device switcher -->
            <div class="p-5 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-800" id="previewModalSlotTitle">Hero Banner Preview</h3>
                        <span class="text-xs text-stone-400 font-medium" id="previewModalSlotPath">Homepage → Hero Banner</span>
                    </div>
                </div>

                <!-- Device Preview Tabs -->
                <div class="bg-stone-200/80 p-1 rounded-2xl flex items-center gap-1">
                    <button onclick="setPreviewDevice('desktop')" id="previewTabDesktop" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-stone-800 shadow-xs flex items-center gap-1.5">
                        <i class="fas fa-desktop"></i>
                        <span>Desktop</span>
                    </button>
                    <button onclick="setPreviewDevice('tablet')" id="previewTabTablet" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-stone-500 hover:text-stone-800 flex items-center gap-1.5">
                        <i class="fas fa-tablet-alt"></i>
                        <span>Tablet</span>
                    </button>
                    <button onclick="setPreviewDevice('mobile')" id="previewTabMobile" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-stone-500 hover:text-stone-800 flex items-center gap-1.5">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Mobile</span>
                    </button>
                </div>

                <button onclick="closeDevicePreviewModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Preview Canvas -->
            <div class="p-8 flex-1 bg-stone-100 overflow-y-auto flex items-center justify-center min-h-[360px]">
                <div id="previewFrame" class="bg-white rounded-2xl shadow-xl overflow-hidden border border-stone-300 transition-all duration-300 w-full max-w-full">
                    <div class="bg-stone-200 px-4 py-2 border-b border-stone-300 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span class="text-[10px] font-mono text-stone-500 ml-2" id="previewFrameResolution">1920 × 700</span>
                    </div>
                    <div class="p-4 flex items-center justify-center bg-[#FFF9F6]">
                        <img id="previewModalImg" src="" alt="" class="max-h-[50vh] w-auto object-contain rounded-xl shadow-xs">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL 3: EDIT SECTION OPTIONS & METADATA MODAL -->
    <div id="editMetadataModal" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100">
            <!-- Pinned Header -->
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div>
                    <h3 class="text-base font-bold text-stone-800" id="editModalHeaderTitle">Edit Section Content & Options</h3>
                    <p class="text-xs text-stone-500 font-medium" id="editModalHeaderSubtitle">Customize headings, button text, and visibility</p>
                </div>
                <button type="button" onclick="closeEditMetadataModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Wrapper -->
            <form id="editMetadataForm" onsubmit="handleMetadataSubmit(event)" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                <input type="hidden" id="editMediaId">
                <input type="hidden" id="editMediaPage">
                <input type="hidden" id="editMediaSection">
                <input type="hidden" id="editMediaSlot">
                
                <!-- Scrollable Body Content -->
                <div class="p-6 space-y-4 overflow-y-auto flex-1 overscroll-contain">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Title / Headline</label>
                            <input type="text" id="editMediaTitle" placeholder="e.g. Custom Crochet" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Subtitle / Highlight</label>
                            <input type="text" id="editMediaSubtitle" placeholder="e.g. Just for You" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Description Paragraph</label>
                        <textarea id="editMediaDesc" rows="2" placeholder="e.g. Your imagination, our yarn. Let's create something special together." class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Button Label (CTA)</label>
                            <input type="text" id="editMediaCtaText" placeholder="e.g. Request Your Custom Order" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Button Link URL</label>
                            <input type="text" id="editMediaCtaLink" placeholder="e.g. /custom-order" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Hanging Tag / Badge Text</label>
                            <input type="text" id="editMediaTagText" placeholder="e.g. Turn Your Ideas Into Handmade Reality" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Alt Text (SEO & Accessibility)</label>
                            <input type="text" id="editMediaAlt" placeholder="e.g. Handmade crochet creations" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Device</label>
                            <select id="editMediaDevice" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800">
                                <option value="all">All Devices</option>
                                <option value="desktop">Desktop</option>
                                <option value="mobile">Mobile</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Sort Order</label>
                            <input type="number" id="editMediaSort" min="0" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="editMediaActive" class="w-4 h-4 rounded text-red-600 focus:ring-red-500">
                        <label for="editMediaActive" class="text-xs font-bold text-stone-700">Active (Visible on Storefront Homepage)</label>
                    </div>
                </div>

                <!-- Sticky Footer with Action Buttons -->
                <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 shrink-0 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditMetadataModal()" class="btn-secondary text-xs px-4 py-2">Cancel</button>
                    <button type="submit" class="btn-primary text-xs px-5 py-2 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i>
                        <span>Save Options</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: HERO SLIDE MODAL (CREATE & EDIT) -->
    <div id="heroSlideModal" onclick="if(event.target === this) closeHeroSlideModal()" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn" onclick="event.stopPropagation()">
            
            <!-- Pinned Header -->
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-base shadow-2xs" id="heroModalIcon">
                        <i class="fas fa-images"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-800" id="heroModalTitle">Add Hero Slide</h3>
                        <p class="text-xs text-stone-500 font-medium" id="heroModalSubtitle">Configure homepage panoramic visual slide & call-to-actions</p>
                    </div>
                </div>
                <button type="button" onclick="closeHeroSlideModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Wrapper -->
            <form id="heroSlideForm" novalidate onsubmit="handleHeroSlideSubmit(event); return false;" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                <input type="hidden" id="heroSlideId" name="id" value="">
                <input type="hidden" id="heroSlideDesktopMediaId" name="desktop_media_id" value="">
                <input type="hidden" id="heroSlideMobileMediaId" name="mobile_media_id" value="">
                <input type="hidden" id="heroSlideDesktopImagePath" name="desktop_image_path" value="">
                <input type="hidden" id="heroSlideMobileImagePath" name="mobile_image_path" value="">

                <!-- Scrollable Body Content -->
                <div class="p-6 space-y-5 overflow-y-auto flex-1 overscroll-contain">
                    
                    <!-- Section: Content Details -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-heading text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Slide Content & Typography</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Slide Title / Headline <span class="text-red-500">*</span></label>
                                <input type="text" id="heroSlideTitle" name="title" placeholder="e.g. Everyday Elegance" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Slide Tagline / Accent Badge</label>
                                <input type="text" id="heroSlideTagline" name="tagline" placeholder="e.g. Handcrafted Bags" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Slide Description</label>
                            <textarea id="heroSlideDesc" name="description" rows="2" placeholder="e.g. Artisanal crochet bags & wearable creations woven with love." class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    <!-- Section: Call-To-Action Buttons -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-mouse-pointer text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Call-to-Action Buttons</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Primary Button Text</label>
                                <input type="text" id="heroSlidePrimaryBtnText" name="primary_button_text" value="Shop Now" placeholder="e.g. Shop Now" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Primary Button Link URL</label>
                                <input type="text" id="heroSlidePrimaryBtnLink" name="primary_button_link" value="/shop" placeholder="e.g. /shop or /category/bags" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Secondary Button Text</label>
                                <input type="text" id="heroSlideSecondaryBtnText" name="secondary_button_text" value="Explore Collections" placeholder="e.g. Explore Collections" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Secondary Button Link URL</label>
                                <input type="text" id="heroSlideSecondaryBtnLink" name="secondary_button_link" value="/shop" placeholder="e.g. /shop" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Media Imagery -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-image text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Slide Imagery (Desktop & Mobile)</span>
                        </div>

                        <!-- Desktop Image Box -->
                        <div class="bg-stone-50/70 border border-stone-200/80 rounded-2xl p-4 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-desktop text-stone-600 text-xs"></i>
                                    <span class="text-xs font-bold text-stone-800">Desktop Image <span class="text-red-500">*</span></span>
                                    <span class="text-[10px] font-bold text-stone-400 bg-stone-200/70 px-2 py-0.5 rounded-md">1920 × 700 px</span>
                                </div>
                                <button type="button" onclick="openMediaPicker('desktop')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-stone-100 border border-stone-200 text-stone-700 text-[11px] font-bold transition-all flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-photo-video text-xs text-red-500"></i>
                                    <span>Select from Media Library</span>
                                </button>
                            </div>

                            <!-- Desktop Dropzone / File Picker -->
                            <div class="border-2 border-dashed border-stone-200 rounded-xl p-3.5 text-center hover:border-red-400 hover:bg-red-50/20 transition-all cursor-pointer"
                                 onclick="document.getElementById('heroSlideDesktopFileInput').click()">
                                <i class="fas fa-cloud-upload-alt text-red-500 text-base mb-1"></i>
                                <p class="text-xs font-bold text-stone-700" id="heroDesktopFileLabel">Click to upload Desktop Image</p>
                                <p class="text-[10px] text-stone-400">JPG, PNG, WEBP (Max 20MB)</p>
                                <input type="file" id="heroSlideDesktopFileInput" name="desktop_image" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" onchange="handleHeroDesktopFileChange(this)">
                            </div>

                            <!-- Desktop Live Preview -->
                            <div id="heroDesktopPreviewContainer" class="hidden bg-white rounded-xl p-2.5 border border-stone-200 flex items-center gap-3">
                                <img id="heroDesktopPreviewImg" src="" class="w-16 h-12 rounded-lg object-cover border border-stone-200">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-stone-800 truncate" id="heroDesktopFileName">Desktop Image Selected</p>
                                    <p class="text-[11px] text-emerald-600 font-semibold" id="heroDesktopFileSize">Ready to save</p>
                                </div>
                                <button type="button" onclick="clearHeroDesktopFileInput()" class="text-stone-400 hover:text-red-600 p-1.5 cursor-pointer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Image Box -->
                        <div class="bg-stone-50/70 border border-stone-200/80 rounded-2xl p-4 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-mobile-alt text-stone-600 text-xs"></i>
                                    <span class="text-xs font-bold text-stone-800">Mobile Image <span class="text-stone-400 text-[11px] font-normal">(Optional — fallback to Desktop)</span></span>
                                    <span class="text-[10px] font-bold text-stone-400 bg-stone-200/70 px-2 py-0.5 rounded-md">768 × 1000 px</span>
                                </div>
                                <button type="button" onclick="openMediaPicker('mobile')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-stone-100 border border-stone-200 text-stone-700 text-[11px] font-bold transition-all flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-photo-video text-xs text-red-500"></i>
                                    <span>Select from Media Library</span>
                                </button>
                            </div>

                            <!-- Mobile Dropzone / File Picker -->
                            <div class="border-2 border-dashed border-stone-200 rounded-xl p-3.5 text-center hover:border-red-400 hover:bg-red-50/20 transition-all cursor-pointer"
                                 onclick="document.getElementById('heroSlideMobileFileInput').click()">
                                <i class="fas fa-cloud-upload-alt text-red-500 text-base mb-1"></i>
                                <p class="text-xs font-bold text-stone-700" id="heroMobileFileLabel">Click to upload Mobile Image</p>
                                <p class="text-[10px] text-stone-400">JPG, PNG, WEBP (Max 20MB)</p>
                                <input type="file" id="heroSlideMobileFileInput" name="mobile_image" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" onchange="handleHeroMobileFileChange(this)">
                            </div>

                            <!-- Mobile Live Preview -->
                            <div id="heroMobilePreviewContainer" class="hidden bg-white rounded-xl p-2.5 border border-stone-200 flex items-center gap-3">
                                <img id="heroMobilePreviewImg" src="" class="w-12 h-14 rounded-lg object-cover border border-stone-200">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-stone-800 truncate" id="heroMobileFileName">Mobile Image Selected</p>
                                    <p class="text-[11px] text-emerald-600 font-semibold" id="heroMobileFileSize">Ready to save</p>
                                </div>
                                <button type="button" onclick="clearHeroMobileFileInput()" class="text-stone-400 hover:text-red-600 p-1.5 cursor-pointer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Section: Slide Settings & Order -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-sliders-h text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Status & Carousel Sequence</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Status</label>
                                <select id="heroSlideStatus" name="status" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="1">Active (Visible on Storefront)</option>
                                    <option value="0">Inactive (Hidden)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Sort Order (Sequence)</label>
                                <input type="number" id="heroSlideSortOrder" name="sort_order" min="1" placeholder="e.g. 1" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Alt Text (SEO)</label>
                                <input type="text" id="heroSlideAlt" name="alt_text" placeholder="e.g. Handcrafted Crochet" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sticky Footer with Action Buttons -->
                <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 shrink-0 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeHeroSlideModal()" class="btn-secondary text-xs px-5 py-2.5 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" id="heroSlideSubmitBtn" class="btn-primary text-xs px-6 py-2.5 flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span id="heroSlideSubmitBtnText">Save Slide</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL 5: MEDIA LIBRARY PICKER MODAL -->
    <div id="mediaPickerModal" onclick="if(event.target === this) closeMediaPicker()" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[85vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn" onclick="event.stopPropagation()">
            
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                        <i class="fas fa-photo-video"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-800">Select Image from Media Library</h3>
                        <p class="text-xs text-stone-500 font-medium">Choose an existing media file from your catalogue</p>
                    </div>
                </div>
                <button type="button" onclick="closeMediaPicker()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="p-4 border-b border-stone-100 bg-white">
                <div class="relative">
                    <input type="text" id="mediaPickerSearch" oninput="filterMediaPicker(this.value)" placeholder="Search media library by file name or title..." class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-search absolute left-3.5 top-3 text-stone-400 text-xs"></i>
                </div>
            </div>

            <!-- Media Grid -->
            <div class="p-6 overflow-y-auto flex-1 overscroll-contain">
                <div id="mediaPickerGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <div class="col-span-full py-8 text-center text-stone-400">Loading media library...</div>
                </div>
            </div>

            <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 flex justify-end">
                <button type="button" onclick="closeMediaPicker()" class="btn-secondary text-xs px-4 py-2 cursor-pointer">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL 6: CUSTOMER REVIEW (TESTIMONIAL) MODAL -->
    <div id="testimonialModal" onclick="if(event.target === this) closeTestimonialModal()" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn" onclick="event.stopPropagation()">
            <!-- Pinned Header -->
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-base shadow-2xs">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-800" id="testimonialModalTitle">Add Customer Review</h3>
                        <p class="text-xs text-stone-500 font-medium">Customer reviews rotate in the homepage reviews carousel</p>
                    </div>
                </div>
                <button type="button" onclick="closeTestimonialModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Wrapper -->
            <form id="testimonialForm" onsubmit="handleTestimonialSubmit(event)" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                <input type="hidden" id="testimonialId" name="id" value="">
                
                <!-- Scrollable Body Content -->
                <div class="p-6 space-y-4 overflow-y-auto flex-1 overscroll-contain">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Customer Name <span class="text-red-500">*</span></label>
                            <input type="text" id="testimonialName" name="name" required placeholder="e.g. Priya Sharma" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Location / Tagline</label>
                            <input type="text" id="testimonialDesignation" name="designation" placeholder="e.g. Bengaluru, India or Verified Patron" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Star Rating <span class="text-red-500">*</span></label>
                            <select id="testimonialRating" name="rating" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="5">★★★★★ (5 Stars - Exceptional)</option>
                                <option value="4">★★★★☆ (4 Stars - Very Good)</option>
                                <option value="3">★★★☆☆ (3 Stars - Good)</option>
                                <option value="2">★★☆☆☆ (2 Stars - Fair)</option>
                                <option value="1">★☆☆☆☆ (1 Star - Poor)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Status</label>
                            <div class="flex items-center gap-2 pt-2.5">
                                <input type="checkbox" id="testimonialActive" name="is_active" checked class="w-4 h-4 rounded text-red-600 focus:ring-red-500">
                                <label for="testimonialActive" class="text-xs font-bold text-stone-700">Show on Website Carousel</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Customer Review Message <span class="text-red-500">*</span></label>
                        <textarea id="testimonialMessage" name="message" rows="3" required placeholder="Write customer feedback or quote..." class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                    </div>
                </div>

                <!-- Sticky Footer with Action Buttons -->
                <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 shrink-0 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeTestimonialModal()" class="btn-secondary text-xs px-5 py-2.5 cursor-pointer">Cancel</button>
                    <button type="submit" id="testimonialSubmitBtn" class="btn-primary text-xs px-6 py-2.5 flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span id="testimonialSubmitBtnText">Save Review</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 7: ADD / EDIT VIDEO & REEL MODAL -->
    <div id="videoReelModal" onclick="if(event.target === this) closeVideoReelModal()" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn" onclick="event.stopPropagation()">
            
            <!-- Pinned Header -->
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-base shadow-2xs">
                        <i class="fas fa-play-circle text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-800" id="videoReelModalTitle">Add Video / Reel</h3>
                        <p class="text-xs text-stone-500 font-medium" id="videoReelModalSubtitle">Manage website video reels, studio journals, tutorials, and social content</p>
                    </div>
                </div>
                <button type="button" onclick="closeVideoReelModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Wrapper -->
            <form id="videoReelForm" novalidate onsubmit="handleVideoReelSubmit(event); return false;" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                <input type="hidden" id="videoReelId" name="id" value="">
                <input type="hidden" id="videoReelThumbnailMediaId" name="thumbnail_media_id" value="">
                <input type="hidden" id="videoReelThumbnailUrl" name="thumbnail_url" value="">

                <!-- Scrollable Body Content -->
                <div class="p-6 space-y-5 overflow-y-auto flex-1 overscroll-contain">
                    
                    <!-- Section: Content Information -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-info-circle text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Content & Category</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Content Type <span class="text-red-500">*</span></label>
                                <select id="videoReelContentType" name="content_type" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="reel">Reel (Vertical 9:16)</option>
                                    <option value="video">Video (Standard / Adaptive)</option>
                                    <option value="blog_video">Blog / Video Story</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Category / Label <span class="text-red-500">*</span></label>
                                <input type="text" id="videoReelCategory" name="category_name" required placeholder="e.g. Studio ASMR" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                <div class="flex flex-wrap gap-1.5 mt-1.5">
                                    <button type="button" onclick="setReelCategory('Studio ASMR')" class="text-[10px] bg-stone-100 hover:bg-red-50 hover:text-red-700 px-2 py-0.5 rounded-md font-semibold text-stone-600 transition-colors">Studio ASMR</button>
                                    <button type="button" onclick="setReelCategory('Style Guide')" class="text-[10px] bg-stone-100 hover:bg-red-50 hover:text-red-700 px-2 py-0.5 rounded-md font-semibold text-stone-600 transition-colors">Style Guide</button>
                                    <button type="button" onclick="setReelCategory('Behind the Scenes')" class="text-[10px] bg-stone-100 hover:bg-red-50 hover:text-red-700 px-2 py-0.5 rounded-md font-semibold text-stone-600 transition-colors">Behind the Scenes</button>
                                    <button type="button" onclick="setReelCategory('Masterclass')" class="text-[10px] bg-stone-100 hover:bg-red-50 hover:text-red-700 px-2 py-0.5 rounded-md font-semibold text-stone-600 transition-colors">Masterclass</button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Title / Headline <span class="text-red-500">*</span></label>
                            <input type="text" id="videoReelTitle" name="title" required placeholder="e.g. Crafting the Everlasting Sunflower" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Tagline / Subtitle</label>
                            <input type="text" id="videoReelTagline" name="tagline" placeholder="e.g. Watch the petal-by-petal stitch technique & stem wiring" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Description Paragraph / Story</label>
                            <textarea id="videoReelDesc" name="description" rows="2" placeholder="Detailed description or story of this video/reel..." class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                        </div>
                    </div>

                    <!-- Section: Video File / URL & Live Player Preview -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-video text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Video Source & Live Player Preview</span>
                        </div>

                        <!-- Video File Dropzone -->
                        <div class="bg-stone-50/80 border border-stone-200/90 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-stone-800">Upload Video File (MP4, WEBM, MOV)</span>
                                <span class="text-[10px] font-bold text-stone-400 bg-stone-200/70 px-2 py-0.5 rounded-md">Max 100MB</span>
                            </div>

                            <div class="border-2 border-dashed border-stone-200 rounded-xl p-3.5 text-center hover:border-red-400 hover:bg-red-50/20 transition-all cursor-pointer"
                                 onclick="document.getElementById('videoReelFileInput').click()">
                                <i class="fas fa-file-video text-red-500 text-xl mb-1"></i>
                                <p class="text-xs font-bold text-stone-700" id="videoReelFileLabel">Click or drop MP4 / WEBM / MOV video file</p>
                                <p class="text-[10px] text-stone-400">Stores directly in storage & saves path in database</p>
                                <input type="file" id="videoReelFileInput" name="video_file" class="hidden" accept="video/mp4,video/webm,video/quicktime,video/ogg,video/x-matroska" onchange="handleVideoFileInputChange(this)">
                            </div>

                            <div class="relative flex py-1 items-center">
                                <div class="flex-grow border-t border-stone-200"></div>
                                <span class="flex-shrink mx-3 text-stone-400 text-[10px] font-bold uppercase tracking-wider">OR ENTER DIRECT VIDEO URL</span>
                                <div class="flex-grow border-t border-stone-200"></div>
                            </div>

                            <div>
                                <input type="text" id="videoReelUrlInput" name="video_url" oninput="handleVideoUrlInputChange(this.value)" placeholder="https://example.com/sample-video.mp4 or /storage/videos/..." class="w-full bg-white border border-stone-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-stone-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>

                            <!-- Live Video Player Preview Container -->
                            <div id="videoPlayerPreviewContainer" class="bg-black rounded-2xl overflow-hidden border border-stone-800 p-2 text-center space-y-2">
                                <div class="flex items-center justify-between px-2 text-stone-400 text-[10px] font-bold uppercase">
                                    <span class="flex items-center gap-1.5"><i class="fas fa-play text-red-500"></i> Video Preview Player</span>
                                    <span id="videoPlayerStatusBadge" class="text-emerald-400 font-normal">Ready to play</span>
                                </div>
                                <div class="relative w-full aspect-video max-h-56 bg-stone-900 rounded-xl overflow-hidden flex items-center justify-center">
                                    <video id="videoReelPreviewPlayer" controls playsinline class="w-full h-full object-contain"></video>
                                    <div id="videoPlayerEmptyState" class="absolute inset-0 flex flex-col items-center justify-center text-stone-500 bg-stone-900/90 pointer-events-none">
                                        <i class="fas fa-film text-3xl mb-1.5 text-stone-600"></i>
                                        <p class="text-xs font-medium">Select a video file or enter a video URL to preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Cover Thumbnail Imagery -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-image text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Cover Image / Thumbnail</span>
                        </div>

                        <div class="bg-stone-50/80 border border-stone-200/90 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-stone-800">Thumbnail Cover <span class="text-red-500">*</span></span>
                                    <span class="text-[10px] font-bold text-stone-400 bg-stone-200/70 px-2 py-0.5 rounded-md">800 × 1200 px (9:15)</span>
                                </div>
                                <button type="button" onclick="openMediaPicker('video_thumbnail')" class="px-2.5 py-1 rounded-lg bg-white hover:bg-stone-100 border border-stone-200 text-stone-700 text-[11px] font-bold transition-all flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-photo-video text-xs text-red-500"></i>
                                    <span>Select from Media Library</span>
                                </button>
                            </div>

                            <div class="border-2 border-dashed border-stone-200 rounded-xl p-3.5 text-center hover:border-red-400 hover:bg-red-50/20 transition-all cursor-pointer"
                                 onclick="document.getElementById('videoReelThumbnailInput').click()">
                                <i class="fas fa-cloud-upload-alt text-red-500 text-base mb-1"></i>
                                <p class="text-xs font-bold text-stone-700" id="videoReelThumbnailLabel">Upload Cover Thumbnail</p>
                                <p class="text-[10px] text-stone-400">JPG, PNG, WEBP (Max 20MB)</p>
                                <input type="file" id="videoReelThumbnailInput" name="thumbnail_file" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" onchange="handleReelCoverInputChange(this)">
                            </div>

                            <!-- Live Thumbnail Preview Box -->
                            <div id="videoReelThumbnailPreviewContainer" class="hidden bg-white rounded-xl p-2.5 border border-stone-200 flex items-center gap-3">
                                <img id="videoReelThumbnailPreviewImg" src="" class="w-14 h-18 rounded-lg object-cover border border-stone-200">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-stone-800 truncate" id="videoReelThumbnailName">Thumbnail Selected</p>
                                    <p class="text-[11px] text-emerald-600 font-semibold" id="videoReelThumbnailSize">Ready to save</p>
                                </div>
                                <button type="button" onclick="clearReelCoverInput()" class="text-stone-400 hover:text-red-600 p-1.5 cursor-pointer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Video Information & Audio -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-music text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Video Information & Track</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Duration <span class="text-stone-400 text-[10px]">(e.g. 00:48)</span></label>
                                <input type="text" id="videoReelDuration" name="duration" value="00:48" placeholder="00:48" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Audio / Music Name</label>
                                <input type="text" id="videoReelAudioName" name="audio_name" placeholder="e.g. Original Audio • Acoustic Morning" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Social Counters -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-chart-bar text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Social Information & Engagement</span>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Likes Count</label>
                                <input type="number" id="videoReelLikes" name="likes" value="0" min="0" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Comments Count</label>
                                <input type="number" id="videoReelComments" name="comments" value="0" min="0" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Views Count</label>
                                <input type="number" id="videoReelViews" name="views" value="0" min="0" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Settings & Sort Order -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-2 border-b border-stone-100 pb-1.5">
                            <i class="fas fa-sliders-h text-red-600 text-xs"></i>
                            <span class="text-xs font-bold text-stone-700 uppercase tracking-wider">Settings & Sort Order</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Status</label>
                                <select id="videoReelStatus" name="status" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="1">Active (Live on Website)</option>
                                    <option value="0">Inactive (Hidden)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Featured</label>
                                <select id="videoReelFeatured" name="is_featured" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="1">Yes (Featured)</option>
                                    <option value="0">No (Standard)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Display Sort Order</label>
                                <input type="number" id="videoReelSortOrder" name="sort_order" value="1" min="1" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sticky Footer with Action Buttons -->
                <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 shrink-0 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeVideoReelModal()" class="btn-secondary text-xs px-5 py-2.5 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" id="videoReelSubmitBtn" class="btn-primary text-xs px-6 py-2.5 flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span id="videoReelSubmitBtnText">Save Video / Reel</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL 8: BLOG / VIDEOS SECTION SETTINGS MODAL -->
    <div id="blogReelsSettingsModal" onclick="if(event.target === this) closeBlogReelsSettingsModal()" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn" onclick="event.stopPropagation()">
            <!-- Pinned Header -->
            <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-base shadow-2xs">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-stone-800">Behind the Stitches Section Settings</h3>
                        <p class="text-xs text-stone-500 font-medium">Customize section headline, subtitle, Instagram CTA button, and links</p>
                    </div>
                </div>
                <button type="button" onclick="closeBlogReelsSettingsModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Form Wrapper -->
            <form id="blogReelsSettingsForm" onsubmit="handleBlogReelsSettingsSubmit(event)" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                <div class="p-6 space-y-4 overflow-y-auto flex-1 overscroll-contain">
                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Section Title <span class="text-red-500">*</span></label>
                        <input type="text" id="blogReelsSettingsTitle" name="title" required value="Behind the Stitches" placeholder="e.g. Behind the Stitches" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Tagline / Badge Text</label>
                        <input type="text" id="blogReelsSettingsTagText" name="tag_text" value="Studio Journal & Video Reels" placeholder="e.g. Studio Journal & Video Reels" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Section Subtitle Paragraph</label>
                        <textarea id="blogReelsSettingsSubtitle" name="subtitle" rows="3" placeholder="Description of what this section showcases..." class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Social CTA Text</label>
                            <input type="text" id="blogReelsSettingsCtaText" name="cta_text" value="Follow @knotelleindia" placeholder="e.g. Follow @knotelleindia" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-600 uppercase tracking-wider mb-1">Social CTA Link URL</label>
                            <input type="text" id="blogReelsSettingsCtaLink" name="cta_link" value="https://instagram.com/knotelleindia" placeholder="e.g. https://instagram.com/knotelleindia" class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-stone-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="blogReelsSettingsActive" name="is_active" checked class="w-4 h-4 rounded text-red-600 focus:ring-red-500">
                        <label for="blogReelsSettingsActive" class="text-xs font-bold text-stone-700">Section Active (Visible on Public Website)</label>
                    </div>
                </div>

                <!-- Sticky Footer with Action Buttons -->
                <div class="p-4 px-6 bg-stone-50 border-t border-stone-100 shrink-0 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeBlogReelsSettingsModal()" class="btn-secondary text-xs px-5 py-2.5 cursor-pointer">Cancel</button>
                    <button type="submit" id="blogReelsSettingsSubmitBtn" class="btn-primary text-xs px-6 py-2.5 flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span>Save Section Settings</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let managerData = null;
    let currentView = 'sections';
    let currentPreviewUrl = '';

    document.addEventListener('DOMContentLoaded', function() {
        loadManagerData();
    });

    // 1. Fetch and render structured manager data
    async function loadManagerData() {
        const loading = document.getElementById('managerLoading');
        const list = document.getElementById('sectionsList');
        
        loading.classList.remove('hidden');
        list.classList.add('hidden');

        try {
            const res = await axios.get('/admin/media/manager-data');
            if (res.data.success) {
                managerData = res.data.data;
                renderSections(managerData);
            }
        } catch (err) {
            console.error('Failed to load manager data', err);
            toastr.error('Failed to load media manager data.');
        } finally {
            loading.classList.add('hidden');
            list.classList.remove('hidden');
        }
    }

    // 2. Render all section accordions & cards
    function renderSections(data) {
        const container = document.getElementById('sectionsList');
        container.innerHTML = '';

        const selectedPage = document.getElementById('filterPage').value;
        const selectedSection = document.getElementById('filterSection').value;
        const selectedDevice = document.getElementById('filterDevice').value;

        data.sections.forEach(section => {
            // Page filter check
            if (selectedPage !== 'all' && section.page !== selectedPage) {
                return;
            }
            // Section filter check
            if (selectedSection !== 'all' && section.id !== selectedSection) {
                return;
            }

            // Create Section Wrapper Card
            const secCard = document.createElement('div');
            secCard.className = 'bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden';
            secCard.id = `sec-card-${section.id}`;

            // Section Header
            const isHero = section.id === 'hero';
            const isBlogReels = section.id === 'blog_reels' || section.is_blog_reels_section;
            const header = `
                <div class="px-8 py-5 border-b border-stone-100 bg-stone-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 font-bold shadow-2xs">
                            <i class="${getSectionIcon(section.id)}"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-bold text-stone-800">${section.title}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-stone-200/80 text-stone-700">${section.badge}</span>
                            </div>
                            <p class="text-xs text-stone-500 font-medium">${section.description}</p>
                        </div>
                    </div>
                    ${isHero ? `
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openAddHeroSlideModal()" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center gap-2 active:scale-95 cursor-pointer">
                                <i class="fas fa-plus-circle text-sm"></i>
                                <span>+ Add Hero Slide</span>
                            </button>
                        </div>
                    ` : (isBlogReels ? `
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="openBlogReelsSettingsModal()" class="btn-secondary text-xs px-4 py-2.5 flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-cog text-xs"></i>
                                <span>Edit Section Settings</span>
                            </button>
                            <button type="button" onclick="openAddVideoReelModal()" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center gap-2 active:scale-95 cursor-pointer">
                                <i class="fas fa-plus-circle text-sm"></i>
                                <span>+ Add Video / Reel</span>
                            </button>
                        </div>
                    ` : '')}
                </div>
            `;

            // Section Body (Slots / Categories / Product Info)
            let bodyContent = '';

            if (section.is_category_section) {
                // Shop by Category Dynamic Grid
                bodyContent = `
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            ${data.categories.map(cat => renderCategoryCard(cat)).join('')}
                        </div>
                    </div>
                `;
            } else if (section.is_blog_reels_section) {
                // Dynamic Video & Reels Section (Behind the Stitches)
                const reels = section.reels || [];
                const secSettings = section.section_settings || {};
                bodyContent = `
                    <div class="p-8">
                        <div class="bg-[#FFF9F6] border border-[#E7D1CC] rounded-2xl p-5 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold text-base shadow-2xs">
                                    <i class="fas fa-video"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-stone-800 text-sm">${escapeHtml(secSettings.title || 'Behind the Stitches')}</h4>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">${escapeHtml(secSettings.badge || 'Watch & Learn')}</span>
                                    </div>
                                    <p class="text-xs text-stone-500">${escapeHtml(secSettings.description || 'Step inside our atelier. Watch the craft, hear the rhythmic click of hooks, and learn styling tips from our master crocheters.')}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" onclick="openBlogReelsSettingsModal()" class="btn-secondary text-xs px-3.5 py-2 flex items-center gap-1.5 cursor-pointer">
                                    <i class="fas fa-cog text-xs"></i>
                                    <span>Section Settings</span>
                                </button>
                                <button type="button" onclick="openAddVideoReelModal()" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center gap-1.5 active:scale-95 cursor-pointer">
                                    <i class="fas fa-plus-circle text-xs"></i>
                                    <span>+ Add Video / Reel</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            ${reels.length > 0 ? reels.map(r => renderVideoReelCard(r)).join('') : `
                                <div class="col-span-full py-12 text-center text-stone-400 bg-stone-50 rounded-2xl border border-dashed border-stone-200">
                                    <i class="fas fa-film text-3xl mb-2 text-stone-300"></i>
                                    <p class="text-sm font-bold text-stone-600">No videos or reels added yet</p>
                                    <p class="text-xs text-stone-400 mb-4">Click "+ Add Video / Reel" above to publish your first video.</p>
                                </div>
                            `}
                        </div>
                    </div>
                `;
            } else if (section.is_product_section) {
                // Best Sellers Product-Driven Info
                bodyContent = `
                    <div class="p-8">
                        <div class="bg-[#FFF9F6] border border-[#E7D1CC] rounded-2xl p-6 mb-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold">
                                        <i class="fas fa-boxes"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-stone-800 text-sm">Product-Driven Best Sellers</h4>
                                        <p class="text-xs text-stone-500">The Best Sellers section automatically displays products flagged as "Bestseller" or "Featured" in your Products database.</p>
                                    </div>
                                </div>
                                <a href="/admin/products" class="btn-primary text-xs px-4 py-2.5 flex items-center gap-2 shrink-0">
                                    <i class="fas fa-edit"></i>
                                    <span>Manage Products</span>
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            ${data.best_sellers.map(p => `
                                <div class="bg-white border border-stone-200 rounded-2xl p-3 text-center shadow-2xs hover:shadow-xs transition-all">
                                    <img src="${p.image}" class="w-full aspect-square rounded-xl object-cover mb-2 border border-stone-100" onerror="this.src='/images/logo/Logo_1.png'">
                                    <h5 class="text-xs font-bold text-stone-800 truncate" title="${p.name}">${p.name}</h5>
                                    <span class="text-[11px] font-bold text-red-600 block">₹${p.price}</span>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[9px] font-bold ${p.is_bestseller ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700'}">
                                        ${p.is_bestseller ? 'Bestseller' : 'Featured'}
                                    </span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else if (section.is_text_only_section) {
                // Clean Text & Option Manager (Custom Order, Newsletter)
                const m = section.metadata || {};
                bodyContent = `
                    <div class="p-8">
                        <div class="bg-[#FFF9F6] border border-[#E7D1CC] rounded-3xl p-6 sm:p-8 shadow-2xs hover:shadow-boutique transition-all">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                                <div class="lg:col-span-8 space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                            <i class="fas fa-tag mr-1 text-[10px]"></i>${escapeHtml(m.badge || m.tag_text || 'Active Section')}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold ${m.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-500 border border-stone-200'}">
                                            ${m.is_active ? '● Live on Storefront' : '○ Hidden'}
                                        </span>
                                    </div>

                                    <div>
                                        <h3 class="text-xl sm:text-2xl font-bold text-stone-800 tracking-tight">
                                            ${escapeHtml(m.title || m.title_line1 || 'Headline Title')} 
                                            ${(m.subtitle || m.title_line2) ? `<span class="text-[#913638] italic font-serif font-normal block sm:inline sm:ml-1">${escapeHtml(m.subtitle || m.title_line2)}</span>` : ''}
                                        </h3>
                                    </div>

                                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed max-w-2xl">${escapeHtml(m.description || 'No description text set.')}</p>

                                    ${m.cta_text ? `
                                        <div class="pt-1">
                                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white border border-[#E7D1CC] text-xs font-semibold text-stone-700 shadow-2xs">
                                                <i class="fas fa-mouse-pointer text-red-600 text-xs"></i>
                                                <span>Button: "<strong>${escapeHtml(m.cta_text)}</strong>" ${m.cta_link ? `→ ${escapeHtml(m.cta_link)}` : ''}</span>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>

                                <div class="lg:col-span-4 flex lg:justify-end">
                                    <button type="button" onclick="openTextSectionEditModal('${section.page}', '${section.id}')"
                                            class="px-6 py-3.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center gap-2.5 active:scale-95 cursor-pointer">
                                        <i class="fas fa-sliders-h text-sm"></i>
                                        <span>Edit Taglines & Content</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (section.is_testimonial_section) {
                // Customer Reviews & Testimonials Dynamic Cards
                const testimonials = section.testimonials || [];
                bodyContent = `
                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 bg-stone-50 border border-stone-200/80 p-4 rounded-2xl">
                            <div>
                                <h4 class="font-bold text-stone-800 text-sm">Homepage Customer Reviews Carousel</h4>
                                <p class="text-xs text-stone-500 font-medium">Add, edit, or delete customer reviews. Changes update automatically in the storefront carousel.</p>
                            </div>
                            <button type="button" onclick="openAddTestimonialModal()" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-xs hover:shadow-md transition-all flex items-center gap-2 shrink-0 active:scale-95 cursor-pointer">
                                <i class="fas fa-plus-circle text-sm"></i>
                                <span>+ Add Customer Review</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            ${testimonials.length > 0 ? testimonials.map(t => `
                                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-2xs hover:shadow-boutique transition-all flex flex-col justify-between group">
                                    <div>
                                        <div class="flex items-center justify-between gap-2 mb-3">
                                            <div class="flex items-center text-amber-400 text-xs gap-0.5">
                                                ${Array.from({length: t.rating || 5}).map(() => '<i class="fas fa-star"></i>').join('')}
                                            </div>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${t.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-500 border border-stone-200'}">
                                                ${t.is_active ? 'Active' : 'Hidden'}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-red-50 to-rose-100 border border-red-200/80 text-red-700 flex items-center justify-center font-bold text-sm shadow-2xs shrink-0">
                                                ${escapeHtml((t.name || 'K').charAt(0).toUpperCase())}
                                            </div>
                                            <div class="truncate">
                                                <h4 class="font-bold text-stone-800 text-sm truncate">${escapeHtml(t.name)}</h4>
                                                <p class="text-[11px] text-stone-400 truncate">${escapeHtml(t.designation || 'Verified Patron')}</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-stone-600 italic leading-relaxed line-clamp-4 mb-4">"${escapeHtml(t.message)}"</p>
                                    </div>
                                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-2">
                                        <button type="button" onclick="openEditTestimonialModal(${t.id})" class="flex-1 py-1.5 px-3 rounded-xl bg-red-50 text-red-700 hover:bg-red-600 hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                            <i class="fas fa-edit text-xs"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" onclick="deleteTestimonial(${t.id})" class="py-1.5 px-3 rounded-xl bg-stone-50 hover:bg-rose-50 text-stone-400 hover:text-rose-600 text-xs font-bold transition-all cursor-pointer" title="Delete Review">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            `).join('') : `
                                <div class="col-span-full py-12 text-center text-stone-400 bg-stone-50 rounded-2xl border border-dashed border-stone-200">
                                    <i class="fas fa-star text-3xl mb-2 text-stone-300"></i>
                                    <p class="text-sm font-bold text-stone-600">No customer reviews yet</p>
                                    <p class="text-xs text-stone-400 mb-4">Click "+ Add Customer Review" to add one.</p>
                                </div>
                            `}
                        </div>
                    </div>
                `;
            } else if (section.is_content_section) {
                // Trust & Benefits Content-Driven Info
                bodyContent = `
                    <div class="p-8">
                        <div class="bg-[#FFF9F6] border border-[#E7D1CC] rounded-2xl p-6">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-heart text-red-600 text-xl"></i>
                                <div>
                                    <h4 class="font-bold text-stone-800 text-sm">Icon & Content Driven Section</h4>
                                    <p class="text-xs text-stone-500">The 4 benefits ("Handmade with Love", "Premium Yarn Quality", "100% Pure Natural Cotton", "Happiness Guaranteed") use SVG artwork and boutique typography.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else if (section.slots && section.slots.length > 0) {
                // Regular Slot Cards
                const filteredSlots = section.slots.filter(s => {
                    if (selectedDevice !== 'all' && s.device !== 'all' && s.device !== selectedDevice) {
                        return false;
                    }
                    return true;
                });

                bodyContent = `
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            ${filteredSlots.map(s => renderSlotCard(s)).join('')}
                        </div>
                    </div>
                `;
            }

            secCard.innerHTML = header + bodyContent;
            container.appendChild(secCard);
        });
    }

    // 3. Render individual Slot Card
    function renderSlotCard(slot) {
        const hasMedia = slot.media && slot.media.url;
        const imgUrl = hasMedia ? slot.media.url : '/images/logo/Logo_1.png';
        const isActive = slot.media ? slot.media.is_active : true;
        const m = slot.media || {};
        const isHero = slot.section === 'hero';
        const isCustomHero = isHero && (slot.is_custom_slide || m.id);
        const slideId = m.id || slot.media_id;

        if (isHero) {
            return `
                <div class="bg-white border border-stone-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-boutique transition-all flex flex-col justify-between group">
                    <div>
                        <!-- Hero Slide Header Badges -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                    Slide #${m.sort_order || slot.sort_order || 1}
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider ${getDeviceBadgeClass(slot.device || m.device || 'desktop')}">
                                    <i class="${getDeviceIcon(slot.device || m.device || 'desktop')} mr-1"></i>${slot.device || m.device || 'desktop'}
                                </span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold ${isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-500 border border-stone-200'}">
                                ${isActive ? '● Active' : '○ Inactive'}
                            </span>
                        </div>

                        <!-- Image Preview Container -->
                        <div class="relative w-full aspect-[16/9] rounded-xl overflow-hidden bg-[#FFF9F6] border border-stone-200/70 mb-3.5 flex items-center justify-center cursor-pointer group-hover:border-red-300 transition-colors"
                             onclick="handleSlotPreviewClick('${slot.page}', '${slot.section}', '${slot.slot}')">
                            <img src="${m.desktop_image || imgUrl}" alt="${escapeHtml(m.title || slot.title)}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-300" onerror="this.src='/images/logo/Logo_1.png'">
                            
                            <div class="absolute inset-0 bg-stone-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <span class="px-3 py-1.5 rounded-full bg-white text-stone-800 text-xs font-bold shadow-md flex items-center gap-1">
                                    <i class="fas fa-search-plus text-xs"></i> Preview
                                </span>
                            </div>

                            ${m.mobile_image && m.mobile_image !== m.desktop_image ? `
                                <div class="absolute bottom-2 right-2 bg-stone-900/75 backdrop-blur-xs text-white text-[9px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                    <i class="fas fa-mobile-alt"></i> Mobile Visual Set
                                </div>
                            ` : ''}
                        </div>

                        <!-- Title & Tagline -->
                        <div class="mb-2">
                            <h4 class="font-bold text-stone-800 text-sm mb-0.5 line-clamp-1">${escapeHtml(m.title || slot.title)}</h4>
                            ${(m.tagline || m.tag_text) ? `<p class="text-[11px] font-semibold text-red-600 italic line-clamp-1">${escapeHtml(m.tagline || m.tag_text)}</p>` : ''}
                        </div>

                        <!-- Description Snippet -->
                        <p class="text-[11px] text-stone-500 mb-3 leading-relaxed line-clamp-2">${escapeHtml(m.description || slot.description || 'No description provided.')}</p>

                        <!-- CTA Buttons Info Pills -->
                        <div class="space-y-1.5 mb-3 bg-stone-50 p-2.5 rounded-xl border border-stone-100 text-[10px] font-semibold text-stone-600">
                            <div class="flex items-center justify-between">
                                <span class="text-stone-400">Primary CTA:</span>
                                <span class="font-bold text-stone-800 truncate max-w-[140px]">${escapeHtml(m.primary_button_text || m.cta_text || 'Shop Now')} → ${escapeHtml(m.primary_button_link || m.cta_link || '/shop')}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-stone-400">Secondary CTA:</span>
                                <span class="font-bold text-stone-800 truncate max-w-[140px]">${escapeHtml(m.secondary_button_text || m.secondary_cta_text || 'Explore Collections')} → ${escapeHtml(m.secondary_button_link || m.secondary_cta_link || '/shop')}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Card Actions Toolbar -->
                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-2">
                        <button type="button" onclick="openEditHeroSlideModal(${slideId || 0}, '${slot.slot}')"
                                class="flex-1 py-2 px-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-2xs hover:shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95 cursor-pointer">
                            <i class="fas fa-edit text-xs"></i>
                            <span>Edit Slide</span>
                        </button>

                        <button type="button" onclick="deleteHeroSlide(${slideId || 0})"
                                class="py-2 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition-all flex items-center gap-1 cursor-pointer" title="Delete Hero Slide">
                            <i class="fas fa-trash-alt text-xs"></i>
                            <span>Delete</span>
                        </button>
                    </div>

                </div>
            `;
        }

        return `
            <div class="bg-white border border-stone-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-boutique transition-all flex flex-col justify-between group">
                
                <div>
                    <!-- Slot Header & Badges -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ${getDeviceBadgeClass(slot.device)}">
                            <i class="${getDeviceIcon(slot.device)} mr-1"></i>${slot.device}
                        </span>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold ${isActive && hasMedia ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-500'}">
                            ${hasMedia ? (isActive ? 'Active' : 'Inactive') : 'Default Fallback'}
                        </span>
                    </div>

                    <!-- Image Preview Container -->
                    <div class="relative w-full aspect-[16/9] rounded-xl overflow-hidden bg-[#FFF9F6] border border-stone-200/70 mb-3.5 flex items-center justify-center cursor-pointer group-hover:border-red-300 transition-colors"
                         onclick="handleSlotPreviewClick('${slot.page}', '${slot.section}', '${slot.slot}')">
                        <img src="${imgUrl}" alt="${escapeHtml(m.title || slot.title)}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-300" onerror="this.src='/images/logo/Logo_1.png'">
                        
                        <div class="absolute inset-0 bg-stone-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <span class="px-3 py-1.5 rounded-full bg-white text-stone-800 text-xs font-bold shadow-md flex items-center gap-1">
                                <i class="fas fa-search-plus text-xs"></i> Preview
                            </span>
                        </div>
                    </div>

                    <!-- Title & Slot Info -->
                    <h4 class="font-bold text-stone-800 text-sm mb-0.5">${escapeHtml(m.title || slot.title)}</h4>
                    ${m.subtitle ? `<p class="text-[11px] font-semibold text-red-600 italic mb-1">${escapeHtml(m.subtitle)}</p>` : ''}
                    <p class="text-[11px] text-stone-500 mb-2 leading-relaxed line-clamp-2">${escapeHtml(m.description || slot.description)}</p>
                    
                    ${m.cta_text ? `
                        <div class="mb-2 text-[10px] font-bold text-stone-600 bg-stone-50 px-2.5 py-1.5 rounded-lg flex items-center gap-1">
                            <i class="fas fa-link text-red-500 text-[10px]"></i>
                            <span>Button: "${escapeHtml(m.cta_text)}"</span>
                        </div>
                    ` : ''}

                    <div class="flex items-center justify-between text-[11px] text-stone-400 font-semibold bg-stone-50 px-2.5 py-1.5 rounded-lg mb-3">
                        <span>Recommended:</span>
                        <span class="font-bold text-stone-700">${slot.recommended_dimensions}</span>
                    </div>
                </div>

                <!-- Card Actions Toolbar -->
                <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-2">
                    <button onclick="handleSlotReplaceClick('${slot.page}', '${slot.section}', '${slot.slot}')"
                            class="flex-1 py-2 px-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-2xs hover:shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i class="fas fa-camera text-xs"></i>
                        <span>Replace Image</span>
                    </button>

                    <button onclick="handleSlotOptionsClick('${slot.page}', '${slot.section}', '${slot.slot}')"
                            class="py-2 px-3 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition-all flex items-center gap-1" title="Edit Options & Content">
                        <i class="fas fa-sliders-h text-xs"></i>
                        <span>Options</span>
                    </button>

                    ${hasMedia ? `
                        <button onclick="detachSlot('${slot.page}', '${slot.section}', '${slot.slot}')"
                                class="p-2 rounded-xl bg-stone-100 hover:bg-rose-50 text-stone-400 hover:text-rose-600 text-xs font-bold transition-all" title="Detach Slot">
                            <i class="fas fa-unlink"></i>
                        </button>
                    ` : ''}
                </div>

            </div>
        `;
    }

    // 4. Render Category Card
    function renderCategoryCard(cat) {
        return `
            <div class="bg-white border border-stone-200 rounded-2xl p-4 shadow-2xs hover:shadow-boutique transition-all flex flex-col items-center text-center group">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-[#E7D1CC] shadow-xs mb-3 bg-[#FFF9F6]">
                    <img src="${cat.image_url}" alt="${escapeHtml(cat.name)}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" onerror="this.src='/images/categories/${cat.slug}.jpg'">
                </div>
                
                <h4 class="font-bold text-stone-800 text-sm mb-0.5">${escapeHtml(cat.name)}</h4>
                <span class="text-[11px] text-stone-500 font-semibold mb-3">${cat.product_count} Active Products</span>

                <div class="w-full pt-3 border-t border-stone-100 flex items-center justify-center gap-2">
                    <button onclick="openCategoryUploadModal(${cat.id})"
                            class="w-full py-1.5 px-3 rounded-xl bg-red-50 text-red-700 hover:bg-red-600 hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                        <i class="fas fa-upload text-[10px]"></i>
                        <span>Replace Image</span>
                    </button>
                </div>
            </div>
        `;
    }

    // Helpers
    function findSlot(page, section, slotKey) {
        if (!managerData || !managerData.sections) return null;
        const sec = managerData.sections.find(s => s.id === section);
        if (!sec || !sec.slots) return null;
        return sec.slots.find(s => s.slot === slotKey) || null;
    }

    function getSectionIcon(id) {
        switch (id) {
            case 'hero': return 'fas fa-images';
            case 'blog_reels': return 'fas fa-video';
            case 'categories': return 'fas fa-th-large';
            case 'custom_crochet': return 'fas fa-cut';
            case 'brand_story': return 'fas fa-book-open';
            case 'bestsellers': return 'fas fa-fire';
            case 'trust_benefits': return 'fas fa-shield-alt';
            case 'custom_order': return 'fas fa-magic';
            case 'newsletter': return 'fas fa-envelope-open-text';
            case 'footer': return 'fas fa-shoe-prints';
            case 'shop_banner': return 'fas fa-shopping-bag';
            case 'shop_promo': return 'fas fa-tag';
            case 'custom_order_header': return 'fas fa-paint-brush';
            case 'custom_order_showcase': return 'fas fa-camera-retro';
            case 'about_story': return 'fas fa-heart';
            case 'contact_header': return 'fas fa-envelope';
            case 'global_assets': return 'fas fa-globe';
            default: return 'fas fa-folder';
        }
    }

    function getDeviceBadgeClass(device) {
        switch (device) {
            case 'desktop': return 'bg-blue-50 text-blue-700 border border-blue-200';
            case 'mobile': return 'bg-purple-50 text-purple-700 border border-purple-200';
            default: return 'bg-stone-100 text-stone-700 border border-stone-200';
        }
    }

    function getDeviceIcon(device) {
        switch (device) {
            case 'desktop': return 'fas fa-desktop';
            case 'mobile': return 'fas fa-mobile-alt';
            default: return 'fas fa-globe';
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function applyFilters() {
        if (managerData) {
            renderSections(managerData);
        }
    }

    function switchView(view) {
        currentView = view;
        const secContainer = document.getElementById('sectionsViewContainer');
        const libContainer = document.getElementById('libraryViewContainer');
        const secBtn = document.getElementById('viewSectionsBtn');
        const libBtn = document.getElementById('viewLibraryBtn');

        if (view === 'sections') {
            secContainer.classList.remove('hidden');
            libContainer.classList.add('hidden');
            secBtn.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white text-red-700 shadow-xs flex items-center gap-1.5';
            libBtn.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all text-stone-500 hover:text-stone-800 flex items-center gap-1.5';
        } else {
            secContainer.classList.add('hidden');
            libContainer.classList.remove('hidden');
            libBtn.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white text-red-700 shadow-xs flex items-center gap-1.5';
            secBtn.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all text-stone-500 hover:text-stone-800 flex items-center gap-1.5';
            initLibraryTable();
        }
    }

    // Slot Modal Handlers
    function handleSlotReplaceClick(page, section, slotKey) {
        const slotDef = findSlot(page, section, slotKey);
        if (!slotDef) {
            toastr.error('Slot definition not found.');
            return;
        }

        const m = slotDef.media || {};
        document.getElementById('targetPage').value = slotDef.page;
        document.getElementById('targetSection').value = slotDef.section;
        document.getElementById('targetSlot').value = slotDef.slot;
        document.getElementById('targetDevice').value = slotDef.device;
        document.getElementById('selectedMediaId').value = m.id || '';

        document.getElementById('slotModalTitle').innerText = `Replace / Configure ${m.title || slotDef.title}`;
        document.getElementById('slotModalSubtitle').innerText = `Target: ${slotDef.page} → ${slotDef.section} → ${slotDef.slot} (${slotDef.device})`;
        document.getElementById('slotModalDimensions').innerText = slotDef.recommended_dimensions || 'Any aspect ratio';
        
        document.getElementById('slotTitleInput').value = m.title || slotDef.title || '';
        document.getElementById('slotSubtitleInput').value = m.subtitle || '';
        document.getElementById('slotDescInput').value = m.description || '';
        document.getElementById('slotCtaTextInput').value = m.cta_text || '';
        document.getElementById('slotCtaLinkInput').value = m.cta_link || '';
        document.getElementById('slotTagTextInput').value = m.tag_text || '';
        document.getElementById('slotAltInput').value = m.alt_text || `${slotDef.title} Knotelle Boutique`;
        clearSlotFileInput();

        const modal = document.getElementById('slotUploadModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function handleSlotOptionsClick(page, section, slotKey) {
        const slotDef = findSlot(page, section, slotKey);
        if (!slotDef) return;

        const m = slotDef.media || {};
        document.getElementById('editMediaId').value = m.id || '';
        document.getElementById('editMediaPage').value = slotDef.page;
        document.getElementById('editMediaSection').value = slotDef.section;
        document.getElementById('editMediaSlot').value = slotDef.slot;

        document.getElementById('editMediaTitle').value = m.title || slotDef.title || '';
        document.getElementById('editMediaSubtitle').value = m.subtitle || '';
        document.getElementById('editMediaDesc').value = m.description || '';
        document.getElementById('editMediaCtaText').value = m.cta_text || '';
        document.getElementById('editMediaCtaLink').value = m.cta_link || '';
        document.getElementById('editMediaTagText').value = m.tag_text || '';
        document.getElementById('editMediaAlt').value = m.alt_text || '';
        document.getElementById('editMediaDevice').value = m.device || slotDef.device || 'all';
        document.getElementById('editMediaSort').value = m.sort_order || slotDef.sort_order || 1;
        document.getElementById('editMediaActive').checked = m.is_active !== false;

        const modal = document.getElementById('editMetadataModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function handleSlotPreviewClick(page, section, slotKey) {
        const slotDef = findSlot(page, section, slotKey);
        if (!slotDef) return;

        const hasMedia = slotDef.media && slotDef.media.url;
        const imgUrl = hasMedia ? slotDef.media.url : '/images/logo/Logo_1.png';
        const title = (slotDef.media && slotDef.media.title) || slotDef.title;
        const path = `${slotDef.page} → ${slotDef.section}`;

        openDevicePreview(imgUrl, title, path);
    }

    function closeSlotUploadModal() {
        const modal = document.getElementById('slotUploadModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function handleSlotFileChange(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('slotFilePreviewImg').src = e.target.result;
                document.getElementById('slotFileName').innerText = file.name;
                document.getElementById('slotFileSize').innerText = (file.size / 1024).toFixed(1) + ' KB';
                document.getElementById('slotFilePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function clearSlotFileInput() {
        document.getElementById('slotFileInput').value = '';
        document.getElementById('slotFilePreviewContainer').classList.add('hidden');
    }

    function broadcastMediaUpdate() {
        const timestamp = Date.now();
        try {
            localStorage.setItem('knotelle_media_updated', timestamp.toString());
        } catch(e){}
        try {
            if ('BroadcastChannel' in window) {
                const channel = new BroadcastChannel('knotelle_media_sync');
                channel.postMessage({ type: 'MEDIA_UPDATED', timestamp: timestamp });
                channel.close();
            }
        } catch(e){}
    }

    function openTextSectionEditModal(page, sectionId) {
        const sec = managerData?.sections?.find(s => s.id === sectionId);
        const m = sec?.metadata || {};

        document.getElementById('editMediaId').value = m.id || '';
        document.getElementById('editMediaPage').value = page || 'homepage';
        document.getElementById('editMediaSection').value = sectionId;
        document.getElementById('editMediaSlot').value = m.slot || 'default';

        document.getElementById('editModalHeaderTitle').innerText = `Edit ${sec?.title || 'Section Content'}`;
        document.getElementById('editModalHeaderSubtitle').innerText = 'Update headlines, taglines, description, and button links';

        document.getElementById('editMediaTitle').value = m.title || m.title_line1 || '';
        document.getElementById('editMediaSubtitle').value = m.subtitle || m.title_line2 || '';
        document.getElementById('editMediaDesc').value = m.description || '';
        document.getElementById('editMediaCtaText').value = m.cta_text || '';
        document.getElementById('editMediaCtaLink').value = m.cta_link || '';
        document.getElementById('editMediaTagText').value = m.badge || m.tag_text || '';
        document.getElementById('editMediaAlt').value = '';
        document.getElementById('editMediaDevice').value = 'all';
        document.getElementById('editMediaSort').value = 1;
        document.getElementById('editMediaActive').checked = m.is_active !== false;

        const modal = document.getElementById('editMetadataModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditMetadataModal() {
        const modal = document.getElementById('editMetadataModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function handleMetadataSubmit(e) {
        if (e) e.preventDefault();
        const mediaId = document.getElementById('editMediaId').value;
        const page = document.getElementById('editMediaPage').value;
        const section = document.getElementById('editMediaSection').value;
        const slot = document.getElementById('editMediaSlot').value;

        const payload = {
            id: mediaId,
            page: page,
            section: section,
            slot: slot,
            title: document.getElementById('editMediaTitle').value,
            subtitle: document.getElementById('editMediaSubtitle').value,
            description: document.getElementById('editMediaDesc').value,
            cta_text: document.getElementById('editMediaCtaText').value,
            cta_link: document.getElementById('editMediaCtaLink').value,
            tag_text: document.getElementById('editMediaTagText').value,
            alt_text: document.getElementById('editMediaAlt').value,
            device: document.getElementById('editMediaDevice').value,
            sort_order: document.getElementById('editMediaSort').value,
            is_active: document.getElementById('editMediaActive').checked ? 1 : 0,
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const endpoint = '/admin/media/update-metadata' + (mediaId ? `/${mediaId}` : '');
            const res = await axios.post(endpoint, payload, {
                headers: { 'X-CSRF-TOKEN': csrfToken || '' }
            });
            if (res.data.success) {
                toastr.success(res.data.message || 'Section options updated successfully!');
                broadcastMediaUpdate();
                closeEditMetadataModal();
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                toastr.error(res.data.message || 'Failed to update section options.');
            }
        } catch (err) {
            console.error('Update metadata error', err);
            toastr.error(err.response?.data?.message || 'Failed to update section options.');
        }
    }

    async function detachSlot(page, section, slotKey) {
        if (!confirm(`Are you sure you want to detach the image from slot "${slotKey}"? It will revert to the default fallback.`)) {
            return;
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await axios.post('/admin/media/detach-slot', {
                page: page,
                section: section,
                slot: slotKey
            }, {
                headers: { 'X-CSRF-TOKEN': csrfToken || '' }
            });
            if (res.data.success) {
                toastr.success(res.data.message || 'Slot detached successfully.');
                broadcastMediaUpdate();
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                toastr.error(res.data.message || 'Failed to detach slot.');
            }
        } catch (err) {
            console.error('Detach slot error', err);
            toastr.error(err.response?.data?.message || 'Failed to detach slot.');
        }
    }

    function openCategoryUploadModal(catId) {
        window.location.href = `/admin/categories/${catId}/edit`;
    }

    async function handleSlotUploadSubmit(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('slotUploadSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading & Applying...';

        const form = document.getElementById('slotUploadForm');
        const formData = new FormData(form);

        try {
            const res = await axios.post('/admin/media/assign-slot', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (res.data.success) {
                toastr.success(res.data.message || 'Slot updated successfully.');
                broadcastMediaUpdate();
                closeSlotUploadModal();
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                toastr.error(res.data.message || 'Failed to update slot.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save & Apply to Website';
            }
        } catch (err) {
            console.error('Slot upload error', err);
            toastr.error(err.response?.data?.message || 'Upload failed. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save & Apply to Website';
        }
    }

    // ==========================================
    // HERO SLIDE MODAL & CRUD HANDLERS
    // ==========================================

    function openAddHeroSlideModal() {
        const form = document.getElementById('heroSlideForm');
        if (form) form.reset();
        document.getElementById('heroSlideId').value = '';
        document.getElementById('heroSlideDesktopMediaId').value = '';
        document.getElementById('heroSlideMobileMediaId').value = '';
        document.getElementById('heroSlideDesktopImagePath').value = '';
        document.getElementById('heroSlideMobileImagePath').value = '';

        document.getElementById('heroModalTitle').innerText = 'Add Hero Slide';
        document.getElementById('heroModalSubtitle').innerText = 'Configure homepage panoramic visual slide & call-to-actions';
        document.getElementById('heroSlideSubmitBtnText').innerText = 'Save Slide';

        clearHeroDesktopFileInput();
        clearHeroMobileFileInput();

        // Calculate next sort order
        let nextSort = 1;
        if (managerData && managerData.sections) {
            const heroSec = managerData.sections.find(s => s.id === 'hero');
            if (heroSec && heroSec.slots) {
                nextSort = heroSec.slots.length + 1;
            }
        }

        document.getElementById('heroSlideTitle').value = '';
        document.getElementById('heroSlideTagline').value = 'Good Things Are Handmade';
        document.getElementById('heroSlideDesc').value = '';
        document.getElementById('heroSlidePrimaryBtnText').value = 'Shop Now';
        document.getElementById('heroSlidePrimaryBtnLink').value = '/shop';
        document.getElementById('heroSlideSecondaryBtnText').value = 'Explore Collections';
        document.getElementById('heroSlideSecondaryBtnLink').value = '/shop';
        document.getElementById('heroSlideStatus').value = '1';
        document.getElementById('heroSlideSortOrder').value = nextSort;
        document.getElementById('heroSlideAlt').value = '';

        const modal = document.getElementById('heroSlideModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                const titleInput = document.getElementById('heroSlideTitle');
                if (titleInput) titleInput.focus();
            }, 100);
        }
    }

    async function openEditHeroSlideModal(slideId, slotKey) {
        const form = document.getElementById('heroSlideForm');
        if (form) form.reset();

        // 1. First look up slot or media in memory for instantaneous modal opening
        let slideData = null;
        let foundSlot = null;

        if (managerData && managerData.sections) {
            const heroSec = managerData.sections.find(s => s.id === 'hero');
            if (heroSec && heroSec.slots) {
                foundSlot = heroSec.slots.find(s => 
                    (slideId && ((s.media && s.media.id == slideId) || s.media_id == slideId)) || 
                    (slotKey && s.slot === slotKey)
                );
                if (foundSlot) {
                    slideData = foundSlot.media || {};
                }
            }
        }

        const effectiveId = slideId || (slideData && slideData.id) || (foundSlot && foundSlot.media_id) || '';

        document.getElementById('heroSlideId').value = effectiveId;
        document.getElementById('heroSlideDesktopMediaId').value = '';
        document.getElementById('heroSlideMobileMediaId').value = '';
        document.getElementById('heroSlideDesktopImagePath').value = (slideData && (slideData.desktop_image_path || slideData.file_path)) || '';
        document.getElementById('heroSlideMobileImagePath').value = (slideData && slideData.mobile_image_path) || '';

        document.getElementById('heroModalTitle').innerText = effectiveId ? `Edit Hero Slide (#${effectiveId})` : 'Edit Hero Slide';
        document.getElementById('heroModalSubtitle').innerText = 'Update slide content, call-to-action buttons, images, and sequence';
        document.getElementById('heroSlideSubmitBtnText').innerText = 'Update Hero Slide';

        clearHeroDesktopFileInput();
        clearHeroMobileFileInput();

        // Populate fields immediately from in-memory data
        if (slideData || foundSlot) {
            document.getElementById('heroSlideTitle').value = (slideData && slideData.title) || (foundSlot && foundSlot.title) || '';
            document.getElementById('heroSlideTagline').value = (slideData && (slideData.tagline || slideData.tag_text)) || '';
            document.getElementById('heroSlideDesc').value = (slideData && (slideData.description || slideData.subtitle)) || (foundSlot && foundSlot.description) || '';
            document.getElementById('heroSlidePrimaryBtnText').value = (slideData && (slideData.primary_button_text || slideData.cta_text)) || 'Shop Now';
            document.getElementById('heroSlidePrimaryBtnLink').value = (slideData && (slideData.primary_button_link || slideData.cta_link)) || '/shop';
            document.getElementById('heroSlideSecondaryBtnText').value = (slideData && (slideData.secondary_button_text || slideData.secondary_cta_text)) || 'Explore Collections';
            document.getElementById('heroSlideSecondaryBtnLink').value = (slideData && (slideData.secondary_button_link || slideData.secondary_cta_link)) || '/shop';
            document.getElementById('heroSlideStatus').value = (slideData && slideData.is_active === false) ? '0' : '1';
            document.getElementById('heroSlideSortOrder').value = (slideData && slideData.sort_order) || (foundSlot && foundSlot.sort_order) || 1;
            document.getElementById('heroSlideAlt').value = (slideData && slideData.alt_text) || '';

            const desktopImg = (slideData && (slideData.desktop_image || slideData.url));
            if (desktopImg) {
                document.getElementById('heroDesktopPreviewImg').src = desktopImg;
                document.getElementById('heroDesktopFileName').innerText = desktopImg.split('/').pop() || 'Current Desktop Image';
                document.getElementById('heroDesktopFileSize').innerText = 'Existing Slide Image';
                document.getElementById('heroDesktopPreviewContainer').classList.remove('hidden');
            }

            const mobileImg = (slideData && slideData.mobile_image);
            if (mobileImg && mobileImg !== desktopImg) {
                document.getElementById('heroMobilePreviewImg').src = mobileImg;
                document.getElementById('heroMobileFileName').innerText = mobileImg.split('/').pop() || 'Current Mobile Image';
                document.getElementById('heroMobileFileSize').innerText = 'Existing Mobile Image';
                document.getElementById('heroMobilePreviewContainer').classList.remove('hidden');
            }
        }

        // Show modal immediately so the user sees it instantly
        const modal = document.getElementById('heroSlideModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                const titleInput = document.getElementById('heroSlideTitle');
                if (titleInput) titleInput.focus();
            }, 100);
        }

        // Sync with server in background if effectiveId exists
        if (effectiveId) {
            try {
                const res = await axios.get(`/admin/media/hero-slide/${effectiveId}`);
                if (res.data && res.data.success) {
                    const s = res.data.data;
                    // Only update if current modal is still for this slide
                    if (document.getElementById('heroSlideId').value == effectiveId) {
                        if (s.title !== undefined) document.getElementById('heroSlideTitle').value = s.title || '';
                        if (s.tagline !== undefined || s.tag_text !== undefined) document.getElementById('heroSlideTagline').value = s.tagline || s.tag_text || '';
                        if (s.description !== undefined || s.subtitle !== undefined) document.getElementById('heroSlideDesc').value = s.description || s.subtitle || '';
                        if (s.primary_button_text !== undefined || s.cta_text !== undefined) document.getElementById('heroSlidePrimaryBtnText').value = s.primary_button_text || s.cta_text || 'Shop Now';
                        if (s.primary_button_link !== undefined || s.cta_link !== undefined) document.getElementById('heroSlidePrimaryBtnLink').value = s.primary_button_link || s.cta_link || '/shop';
                        if (s.secondary_button_text !== undefined || s.secondary_cta_text !== undefined) document.getElementById('heroSlideSecondaryBtnText').value = s.secondary_button_text || s.secondary_cta_text || 'Explore Collections';
                        if (s.secondary_button_link !== undefined || s.secondary_cta_link !== undefined) document.getElementById('heroSlideSecondaryBtnLink').value = s.secondary_button_link || s.secondary_cta_link || '/shop';
                        if (s.is_active !== undefined) document.getElementById('heroSlideStatus').value = s.is_active ? '1' : '0';
                        if (s.sort_order !== undefined) document.getElementById('heroSlideSortOrder').value = s.sort_order || 1;
                        if (s.alt_text !== undefined) document.getElementById('heroSlideAlt').value = s.alt_text || '';
                        
                        if (s.desktop_image_path) document.getElementById('heroSlideDesktopImagePath').value = s.desktop_image_path;
                        if (s.mobile_image_path) document.getElementById('heroSlideMobileImagePath').value = s.mobile_image_path;

                        if (s.desktop_image) {
                            document.getElementById('heroDesktopPreviewImg').src = s.desktop_image;
                            document.getElementById('heroDesktopFileName').innerText = s.desktop_image.split('/').pop() || 'Current Desktop Image';
                            document.getElementById('heroDesktopFileSize').innerText = 'Existing Slide Image';
                            document.getElementById('heroDesktopPreviewContainer').classList.remove('hidden');
                        }

                        if (s.mobile_image && s.mobile_image !== s.desktop_image) {
                            document.getElementById('heroMobilePreviewImg').src = s.mobile_image;
                            document.getElementById('heroMobileFileName').innerText = s.mobile_image.split('/').pop() || 'Current Mobile Image';
                            document.getElementById('heroMobileFileSize').innerText = 'Existing Mobile Image';
                            document.getElementById('heroMobilePreviewContainer').classList.remove('hidden');
                        }
                    }
                }
            } catch (err) {
                console.warn('Hero slide background sync error', err);
            }
        }
    }

    function closeHeroSlideModal() {
        const modal = document.getElementById('heroSlideModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function handleHeroDesktopFileChange(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('heroDesktopPreviewImg').src = e.target.result;
                document.getElementById('heroDesktopFileName').innerText = file.name;
                document.getElementById('heroDesktopFileSize').innerText = (file.size / 1024).toFixed(1) + ' KB (Ready to upload)';
                document.getElementById('heroDesktopPreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function clearHeroDesktopFileInput() {
        const fileInput = document.getElementById('heroSlideDesktopFileInput');
        if (fileInput) fileInput.value = '';
        document.getElementById('heroSlideDesktopMediaId').value = '';
        document.getElementById('heroSlideDesktopImagePath').value = '';
        const preview = document.getElementById('heroDesktopPreviewContainer');
        if (preview) preview.classList.add('hidden');
    }

    function handleHeroMobileFileChange(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('heroMobilePreviewImg').src = e.target.result;
                document.getElementById('heroMobileFileName').innerText = file.name;
                document.getElementById('heroMobileFileSize').innerText = (file.size / 1024).toFixed(1) + ' KB (Ready to upload)';
                document.getElementById('heroMobilePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function clearHeroMobileFileInput() {
        const fileInput = document.getElementById('heroSlideMobileFileInput');
        if (fileInput) fileInput.value = '';
        document.getElementById('heroSlideMobileMediaId').value = '';
        document.getElementById('heroSlideMobileImagePath').value = '';
        const preview = document.getElementById('heroMobilePreviewContainer');
        if (preview) preview.classList.add('hidden');
    }

    async function handleHeroSlideSubmit(e) {
        if (e) e.preventDefault();

        const slideId = document.getElementById('heroSlideId').value;
        const isEdit = Boolean(slideId && parseInt(slideId) > 0);

        const titleInput = document.getElementById('heroSlideTitle');
        if (!titleInput.value.trim()) {
            toastr.warning('Please enter a Slide Title / Headline.');
            titleInput.focus();
            return;
        }

        const desktopFileInput = document.getElementById('heroSlideDesktopFileInput');
        const desktopMediaId = document.getElementById('heroSlideDesktopMediaId').value;
        const desktopImagePath = document.getElementById('heroSlideDesktopImagePath').value;

        if (!isEdit && (!desktopFileInput.files || desktopFileInput.files.length === 0) && !desktopMediaId && !desktopImagePath) {
            toastr.warning('Please upload or select a Desktop Image for the new hero slide.');
            return;
        }

        const submitBtn = document.getElementById('heroSlideSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving Hero Slide...';

        const form = document.getElementById('heroSlideForm');
        const formData = new FormData(form);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken && !formData.has('_token')) {
            formData.append('_token', csrfToken);
        }

        const endpoint = isEdit ? `/admin/media/hero-slide/${slideId}` : '/admin/media/hero-slide';

        try {
            const res = await axios.post(endpoint, formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            });

            if (res.data.success) {
                toastr.success(res.data.message || (isEdit ? 'Hero slide updated successfully!' : 'Hero slide created successfully!'));
                broadcastMediaUpdate();
                closeHeroSlideModal();
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                toastr.error(res.data.message || 'Failed to save hero slide.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save mr-1.5"></i><span>' + (isEdit ? 'Update Hero Slide' : 'Save Slide') + '</span>';
            }
        } catch (err) {
            console.error('Hero slide save error', err);
            const errMsg = err.response?.data?.message || 
                (err.response?.data?.errors ? Object.values(err.response.data.errors).flat().join('<br>') : 'Failed to save hero slide. Please check inputs and file format.');
            toastr.error(errMsg);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save mr-1.5"></i><span>' + (isEdit ? 'Update Hero Slide' : 'Save Slide') + '</span>';
        }
    }

    async function deleteHeroSlide(id) {
        if (!confirm('Are you sure you want to delete this Hero Slide? This will remove it from the storefront carousel.')) {
            return;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await axios.delete(`/admin/media/hero-slide/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            });
            if (res.data.success) {
                toastr.success(res.data.message || 'Hero slide deleted successfully.');
                broadcastMediaUpdate();
                setTimeout(() => {
                    window.location.reload();
                }, 400);
            } else {
                toastr.error(res.data.message || 'Failed to delete hero slide.');
            }
        } catch (err) {
            console.error('Delete hero slide error', err);
            toastr.error(err.response?.data?.message || 'Failed to delete hero slide.');
        }
    }

    // ==========================================
    // ==========================================
    // CUSTOMER REVIEWS (TESTIMONIALS) HANDLERS
    // ==========================================
    function openAddTestimonialModal() {
        const form = document.getElementById('testimonialForm');
        if (form) form.reset();
        document.getElementById('testimonialId').value = '';
        document.getElementById('testimonialModalTitle').innerText = 'Add Customer Review';
        document.getElementById('testimonialSubmitBtnText').innerText = 'Save Review';
        document.getElementById('testimonialName').value = '';
        document.getElementById('testimonialDesignation').value = '';
        document.getElementById('testimonialRating').value = '5';
        document.getElementById('testimonialMessage').value = '';
        document.getElementById('testimonialActive').checked = true;
        
        const modal = document.getElementById('testimonialModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    async function openEditTestimonialModal(id) {
        const form = document.getElementById('testimonialForm');
        if (form) form.reset();
        document.getElementById('testimonialId').value = id;
        document.getElementById('testimonialModalTitle').innerText = `Edit Customer Review (#${id})`;
        document.getElementById('testimonialSubmitBtnText').innerText = 'Update Review';

        const modal = document.getElementById('testimonialModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        try {
            const res = await axios.get(`/admin/media/testimonial/${id}`);
            if (res.data && res.data.success) {
                const t = res.data.data;
                document.getElementById('testimonialName').value = t.name || '';
                document.getElementById('testimonialDesignation').value = t.designation || '';
                document.getElementById('testimonialRating').value = t.rating || 5;
                document.getElementById('testimonialMessage').value = t.message || '';
                document.getElementById('testimonialActive').checked = Boolean(t.is_active);
            }
        } catch (err) {
            console.error('Failed to load testimonial', err);
            toastr.error('Failed to load review details.');
        }
    }

    function closeTestimonialModal() {
        const modal = document.getElementById('testimonialModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function handleTestimonialSubmit(e) {
        if (e) e.preventDefault();
        
        const name = document.getElementById('testimonialName').value.trim();
        const message = document.getElementById('testimonialMessage').value.trim();
        
        if (!name) {
            toastr.error('Please enter customer name.');
            return;
        }
        if (!message) {
            toastr.error('Please enter review message.');
            return;
        }

        const submitBtn = document.getElementById('testimonialSubmitBtn');
        const submitBtnText = document.getElementById('testimonialSubmitBtnText');
        const originalText = submitBtnText ? submitBtnText.innerText : 'Save Review';

        submitBtn.disabled = true;
        if (submitBtnText) submitBtnText.innerText = 'Saving...';

        const form = document.getElementById('testimonialForm');
        const formData = new FormData(form);
        formData.set('is_active', document.getElementById('testimonialActive').checked ? '1' : '0');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken && !formData.has('_token')) {
            formData.append('_token', csrfToken);
        }

        try {
            const res = await axios.post('/admin/media/testimonial', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': csrfToken || ''
                }
            });
            if (res.data.success) {
                toastr.success(res.data.message || 'Review saved successfully!');
                broadcastMediaUpdate();
                closeTestimonialModal();
                loadManagerData();
            } else {
                toastr.error(res.data.message || 'Failed to save review.');
            }
        } catch (err) {
            console.error('Testimonial save error', err);
            toastr.error(err.response?.data?.message || 'Failed to save review.');
        } finally {
            submitBtn.disabled = false;
            if (submitBtnText) submitBtnText.innerText = originalText;
        }
    }

    async function deleteTestimonial(id) {
        if (!confirm('Are you sure you want to delete this customer review?')) return;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
            const res = await axios.delete(`/admin/media/testimonial/${id}`, {
                headers: { 'X-CSRF-TOKEN': csrfToken || '' }
            });
            if (res.data.success) {
                toastr.success(res.data.message || 'Review deleted successfully.');
                broadcastMediaUpdate();
                loadManagerData();
            } else {
                toastr.error(res.data.message || 'Failed to delete review.');
            }
        } catch (err) {
            console.error('Delete review error', err);
            toastr.error(err.response?.data?.message || 'Failed to delete review.');
        }
    }

    // ==========================================
    // MEDIA LIBRARY PICKER MODAL
    // ==========================================
    let pickerTarget = 'desktop';
    let pickerMediaCache = [];

    async function openMediaPicker(target) {
        pickerTarget = target;
        const modal = document.getElementById('mediaPickerModal');
        const grid = document.getElementById('mediaPickerGrid');
        grid.innerHTML = '<div class="col-span-full py-8 text-center text-stone-400"><i class="fas fa-spinner fa-spin mr-2"></i>Loading media library items...</div>';
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        try {
            const res = await axios.get('/admin/media/data?per_page=48');
            pickerMediaCache = res.data.data || [];
            renderMediaPickerGrid(pickerMediaCache);
        } catch (err) {
            console.error('Picker load error', err);
            grid.innerHTML = '<div class="col-span-full py-8 text-center text-rose-500 font-bold">Failed to load media files.</div>';
        }
    }

    function closeMediaPicker() {
        const modal = document.getElementById('mediaPickerModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function renderMediaPickerGrid(items) {
        const grid = document.getElementById('mediaPickerGrid');
        if (!items || items.length === 0) {
            grid.innerHTML = '<div class="col-span-full py-8 text-center text-stone-400">No media files found.</div>';
            return;
        }

        grid.innerHTML = items.map(item => `
            <div onclick="selectMediaFromPicker(${item.id}, '${item.url}', '${escapeHtml(item.name || item.file_name)}')"
                 class="bg-stone-50 hover:bg-red-50/40 border border-stone-200 hover:border-red-400 rounded-2xl p-2.5 flex flex-col items-center cursor-pointer transition-all duration-200 group">
                <div class="relative w-full aspect-square rounded-xl overflow-hidden bg-white border border-stone-100 mb-2">
                    <img src="${item.url}" alt="${escapeHtml(item.name)}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" onerror="this.src='/images/logo/Logo_1.png'">
                </div>
                <p class="text-[11px] font-bold text-stone-700 truncate w-full text-center" title="${escapeHtml(item.name || item.file_name)}">${escapeHtml(item.name || item.file_name)}</p>
                <span class="text-[10px] text-stone-400">${item.size_formatted || ''}</span>
            </div>
        `).join('');
    }

    function filterMediaPicker(query) {
        if (!pickerMediaCache) return;
        const q = (query || '').toLowerCase().trim();
        if (!q) {
            renderMediaPickerGrid(pickerMediaCache);
            return;
        }
        const filtered = pickerMediaCache.filter(m => 
            (m.name && m.name.toLowerCase().includes(q)) ||
            (m.file_name && m.file_name.toLowerCase().includes(q)) ||
            (m.slot && m.slot.toLowerCase().includes(q))
        );
        renderMediaPickerGrid(filtered);
    }

    function selectMediaFromPicker(id, url, name) {
        if (pickerTarget === 'desktop') {
            document.getElementById('heroSlideDesktopMediaId').value = id;
            document.getElementById('heroSlideDesktopImagePath').value = url;
            document.getElementById('heroDesktopPreviewImg').src = url;
            document.getElementById('heroDesktopFileName').innerText = name || 'Media Library Image';
            document.getElementById('heroDesktopFileSize').innerText = 'Selected from Media Library';
            document.getElementById('heroDesktopPreviewContainer').classList.remove('hidden');
        } else if (pickerTarget === 'video_thumbnail') {
            document.getElementById('videoReelThumbnailMediaId').value = id;
            document.getElementById('videoReelThumbnailUrl').value = url;
            document.getElementById('videoReelThumbnailPreviewImg').src = url;
            document.getElementById('videoReelThumbnailName').innerText = name || 'Media Library Image';
            document.getElementById('videoReelThumbnailSize').innerText = 'Selected from Media Library';
            document.getElementById('videoReelThumbnailPreviewContainer').classList.remove('hidden');
        } else {
            document.getElementById('heroSlideMobileMediaId').value = id;
            document.getElementById('heroSlideMobileImagePath').value = url;
            document.getElementById('heroMobilePreviewImg').src = url;
            document.getElementById('heroMobileFileName').innerText = name || 'Media Library Image';
            document.getElementById('heroMobileFileSize').innerText = 'Selected from Media Library';
            document.getElementById('heroMobilePreviewContainer').classList.remove('hidden');
        }
        closeMediaPicker();
        toastr.info(`Selected "${name}" for ${pickerTarget} image.`);
    }

    // ==========================================
    // BLOG / VIDEOS & REELS MANAGEMENT
    // ==========================================
    function renderVideoReelCard(reel) {
        const thumbUrl = reel.thumbnail_url || reel.url || '/images/logo/Logo_1.png';
        const isActive = reel.is_active !== false && reel.is_active !== 0;
        const isFeatured = !!reel.is_featured;

        return `
            <div class="bg-white border border-stone-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-boutique transition-all flex flex-col justify-between group">
                <div>
                    <!-- Header Badges -->
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-wider">
                                ${escapeHtml(reel.category_name || 'Reel')}
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase bg-stone-100 text-stone-600">
                                ${reel.content_type || 'reel'}
                            </span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${isActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-500 border border-stone-200'}">
                            ${isActive ? '● Active' : '○ Inactive'}
                        </span>
                    </div>

                    <!-- Thumbnail with Play Overlay -->
                    <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-stone-900 border border-stone-200/70 mb-3 group/thumb flex items-center justify-center cursor-pointer"
                         onclick="openEditVideoReelModal(${reel.id})">
                        <img src="${thumbUrl}" alt="${escapeHtml(reel.title)}" class="w-full h-full object-cover group-hover/thumb:scale-105 transition-transform duration-300" onerror="this.src='/images/logo/Logo_1.png'">
                        
                        <div class="absolute inset-0 bg-stone-900/30 group-hover/thumb:bg-stone-900/50 transition-colors flex items-center justify-center">
                            <div class="w-11 h-11 rounded-full bg-white/90 group-hover/thumb:bg-white text-red-600 flex items-center justify-center shadow-lg transition-transform group-hover/thumb:scale-110">
                                <i class="fas fa-play text-sm ml-0.5"></i>
                            </div>
                        </div>

                        ${reel.duration ? `
                            <div class="absolute bottom-2 right-2 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1">
                                <i class="far fa-clock text-[9px]"></i>
                                <span>${escapeHtml(reel.duration)}</span>
                            </div>
                        ` : ''}

                        ${isFeatured ? `
                            <div class="absolute top-2 left-2 bg-amber-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 shadow-xs">
                                <i class="fas fa-star text-[8px]"></i>
                                <span>Featured</span>
                            </div>
                        ` : ''}
                    </div>

                    <!-- Title & Tagline -->
                    <div class="mb-2">
                        <h4 class="font-bold text-stone-800 text-sm mb-0.5 line-clamp-1" title="${escapeHtml(reel.title)}">${escapeHtml(reel.title)}</h4>
                        ${reel.tagline ? `<p class="text-[11px] font-semibold text-red-600 italic line-clamp-1">${escapeHtml(reel.tagline)}</p>` : ''}
                    </div>

                    <!-- Description -->
                    <p class="text-[11px] text-stone-500 mb-2.5 leading-relaxed line-clamp-2">${escapeHtml(reel.description || 'No description provided.')}</p>

                    <!-- Social Stats & Audio Track -->
                    <div class="bg-stone-50 rounded-xl p-2 border border-stone-100 space-y-1 mb-3 text-[10px] font-semibold text-stone-600">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1 text-stone-500"><i class="fas fa-eye text-red-500"></i> Views:</span>
                            <span class="font-bold text-stone-800">${(reel.views_count || 0).toLocaleString()}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1 text-stone-500"><i class="fas fa-heart text-red-500"></i> Likes:</span>
                            <span class="font-bold text-stone-800">${(reel.likes_count || 0).toLocaleString()}</span>
                        </div>
                        ${reel.audio_name ? `
                            <div class="flex items-center gap-1 text-stone-600 truncate pt-0.5 border-t border-stone-200/60">
                                <i class="fas fa-music text-red-500 text-[9px] shrink-0"></i>
                                <span class="truncate" title="${escapeHtml(reel.audio_name)}">${escapeHtml(reel.audio_name)}</span>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Actions Toolbar -->
                <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-1.5">
                    <button type="button" onclick="openEditVideoReelModal(${reel.id})"
                            class="flex-1 py-1.5 px-2.5 rounded-xl bg-red-50 hover:bg-red-600 text-red-700 hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-1 cursor-pointer">
                        <i class="fas fa-edit text-xs"></i>
                        <span>Edit</span>
                    </button>
                    <button type="button" onclick="toggleVideoReelStatus(${reel.id})"
                            class="py-1.5 px-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-600 text-xs font-bold transition-all cursor-pointer" title="Toggle Active Status">
                        <i class="fas ${isActive ? 'fa-eye-slash' : 'fa-eye'} text-xs"></i>
                    </button>
                    <button type="button" onclick="deleteVideoReel(${reel.id})"
                            class="py-1.5 px-2 rounded-xl bg-stone-50 hover:bg-rose-50 text-stone-400 hover:text-rose-600 text-xs font-bold transition-all cursor-pointer" title="Delete Video / Reel">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </div>
            </div>
        `;
    }

    function setReelCategory(catName) {
        document.getElementById('videoReelCategory').value = catName;
    }

    function openAddVideoReelModal() {
        document.getElementById('videoReelForm').reset();
        document.getElementById('videoReelId').value = '';
        document.getElementById('videoReelThumbnailMediaId').value = '';
        document.getElementById('videoReelThumbnailUrl').value = '';
        document.getElementById('videoReelModalTitle').innerText = 'Add Video / Reel';
        document.getElementById('videoReelModalSubtitle').innerText = 'Manage website video reels, studio journals, tutorials, and social content';
        document.getElementById('videoReelSubmitBtnText').innerText = 'Save Video / Reel';
        
        document.getElementById('videoReelContentType').value = 'reel';
        document.getElementById('videoReelCategory').value = 'Studio ASMR';
        document.getElementById('videoReelDuration').value = '00:48';
        document.getElementById('videoReelAudioName').value = 'Original Audio • Knotelle Studio';
        document.getElementById('videoReelLikes').value = '120';
        document.getElementById('videoReelComments').value = '18';
        document.getElementById('videoReelViews').value = '1500';
        document.getElementById('videoReelStatus').value = '1';
        document.getElementById('videoReelFeatured').value = '0';
        document.getElementById('videoReelSortOrder').value = '1';

        clearReelCoverInput();
        clearVideoPreviewPlayer();

        const modal = document.getElementById('videoReelModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    async function openEditVideoReelModal(id) {
        try {
            const res = await axios.get(`/admin/media/video-reel/${id}`);
            if (!res.data.success || !res.data.reel) {
                toastr.error('Video reel not found');
                return;
            }

            const reel = res.data.reel;
            document.getElementById('videoReelForm').reset();
            document.getElementById('videoReelId').value = reel.id;
            document.getElementById('videoReelThumbnailMediaId').value = '';
            document.getElementById('videoReelThumbnailUrl').value = reel.thumbnail_url || '';

            document.getElementById('videoReelModalTitle').innerText = `Edit: ${reel.title}`;
            document.getElementById('videoReelModalSubtitle').innerText = `ID #${reel.id} — ${reel.category_name || 'Reel'}`;
            document.getElementById('videoReelSubmitBtnText').innerText = 'Update Video / Reel';

            document.getElementById('videoReelContentType').value = reel.content_type || 'reel';
            document.getElementById('videoReelCategory').value = reel.category_name || '';
            document.getElementById('videoReelTitle').value = reel.title || '';
            document.getElementById('videoReelTagline').value = reel.tagline || '';
            document.getElementById('videoReelDesc').value = reel.description || '';
            document.getElementById('videoReelUrlInput').value = reel.video_url || '';
            document.getElementById('videoReelDuration').value = reel.duration || '';
            document.getElementById('videoReelAudioName').value = reel.audio_name || '';
            document.getElementById('videoReelLikes').value = reel.likes_count || 0;
            document.getElementById('videoReelComments').value = reel.comments_count || 0;
            document.getElementById('videoReelViews').value = reel.views_count || 0;
            document.getElementById('videoReelStatus').value = reel.is_active ? '1' : '0';
            document.getElementById('videoReelFeatured').value = reel.is_featured ? '1' : '0';
            document.getElementById('videoReelSortOrder').value = reel.sort_order || 1;

            const thumbUrl = reel.thumbnail_url || reel.url;
            if (thumbUrl) {
                document.getElementById('videoReelThumbnailPreviewImg').src = thumbUrl;
                document.getElementById('videoReelThumbnailName').innerText = reel.title || 'Current Cover Image';
                document.getElementById('videoReelThumbnailSize').innerText = 'Current thumbnail';
                document.getElementById('videoReelThumbnailPreviewContainer').classList.remove('hidden');
            } else {
                clearReelCoverInput();
            }

            const videoSrc = reel.video_url || (reel.file_path ? `/storage/${reel.file_path}` : '');
            if (videoSrc) {
                loadVideoIntoPreviewPlayer(videoSrc);
            } else {
                clearVideoPreviewPlayer();
            }

            const modal = document.getElementById('videoReelModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } catch (err) {
            console.error('Failed to load video reel for editing', err);
            toastr.error('Failed to load video reel details.');
        }
    }

    function closeVideoReelModal() {
        clearVideoPreviewPlayer();
        const modal = document.getElementById('videoReelModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function handleVideoFileInputChange(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('videoReelFileLabel').innerText = `${file.name} (${(file.size / (1024 * 1024)).toFixed(1)} MB)`;
            const fileUrl = URL.createObjectURL(file);
            loadVideoIntoPreviewPlayer(fileUrl);
        }
    }

    function handleVideoUrlInputChange(val) {
        if (val && val.trim().length > 3) {
            loadVideoIntoPreviewPlayer(val.trim());
        } else {
            clearVideoPreviewPlayer();
        }
    }

    function loadVideoIntoPreviewPlayer(src) {
        const player = document.getElementById('videoReelPreviewPlayer');
        const emptyState = document.getElementById('videoPlayerEmptyState');
        const statusBadge = document.getElementById('videoPlayerStatusBadge');
        
        if (player && emptyState) {
            player.src = src;
            player.load();
            emptyState.classList.add('hidden');
            if (statusBadge) {
                statusBadge.innerText = 'Video loaded & ready';
                statusBadge.className = 'text-emerald-400 font-semibold';
            }
        }
    }

    function clearVideoPreviewPlayer() {
        const player = document.getElementById('videoReelPreviewPlayer');
        const emptyState = document.getElementById('videoPlayerEmptyState');
        const statusBadge = document.getElementById('videoPlayerStatusBadge');
        const fileLabel = document.getElementById('videoReelFileLabel');
        const fileInput = document.getElementById('videoReelFileInput');
        
        if (player) {
            player.pause();
            player.removeAttribute('src');
            player.load();
        }
        if (emptyState) emptyState.classList.remove('hidden');
        if (statusBadge) {
            statusBadge.innerText = 'No video selected';
            statusBadge.className = 'text-stone-400 font-normal';
        }
        if (fileLabel) fileLabel.innerText = 'Click or drop MP4 / WEBM / MOV video file';
        if (fileInput) fileInput.value = '';
    }

    function handleReelCoverInputChange(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('videoReelThumbnailPreviewImg').src = e.target.result;
                document.getElementById('videoReelThumbnailName').innerText = file.name;
                document.getElementById('videoReelThumbnailSize').innerText = (file.size / 1024).toFixed(1) + ' KB';
                document.getElementById('videoReelThumbnailPreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function clearReelCoverInput() {
        document.getElementById('videoReelThumbnailInput').value = '';
        document.getElementById('videoReelThumbnailMediaId').value = '';
        document.getElementById('videoReelThumbnailUrl').value = '';
        document.getElementById('videoReelThumbnailPreviewContainer').classList.add('hidden');
    }

    async function handleVideoReelSubmit(event) {
        if (event) event.preventDefault();
        
        const form = document.getElementById('videoReelForm');
        const id = document.getElementById('videoReelId').value;
        const title = document.getElementById('videoReelTitle').value.trim();
        const category = document.getElementById('videoReelCategory').value.trim();
        
        if (!title) {
            toastr.error('Please enter a title for the video / reel.');
            return;
        }
        if (!category) {
            toastr.error('Please enter or select a category.');
            return;
        }

        const formData = new FormData(form);
        const submitBtn = document.getElementById('videoReelSubmitBtn');
        const submitBtnText = document.getElementById('videoReelSubmitBtnText');
        const originalText = submitBtnText.innerText;

        try {
            submitBtn.disabled = true;
            submitBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';

            let url = '/admin/media/video-reel/add';
            if (id) {
                url = `/admin/media/video-reel/update/${id}`;
            }

            const res = await axios.post(url, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (res.data.success) {
                toastr.success(res.data.message || 'Video / reel saved successfully.');
                closeVideoReelModal();
                broadcastMediaUpdate();
                loadManagerData();
            } else {
                toastr.error(res.data.message || 'Failed to save video / reel.');
            }
        } catch (err) {
            console.error('Video reel save error', err);
            toastr.error(err.response?.data?.message || 'Error saving video / reel.');
        } finally {
            submitBtn.disabled = false;
            submitBtnText.innerText = originalText;
        }
    }

    async function deleteVideoReel(id) {
        if (!confirm('Are you sure you want to delete this Video / Reel? This action cannot be undone.')) {
            return;
        }

        try {
            const res = await axios.delete(`/admin/media/video-reel/${id}`);
            if (res.data.success) {
                toastr.success(res.data.message || 'Video / reel deleted successfully.');
                broadcastMediaUpdate();
                loadManagerData();
            } else {
                toastr.error(res.data.message || 'Failed to delete video / reel.');
            }
        } catch (err) {
            console.error('Failed to delete video reel', err);
            toastr.error(err.response?.data?.message || 'Failed to delete video reel.');
        }
    }

    async function toggleVideoReelStatus(id) {
        try {
            const res = await axios.post(`/admin/media/video-reel/toggle-status/${id}`);
            if (res.data.success) {
                toastr.success(res.data.message || 'Status updated.');
                broadcastMediaUpdate();
                loadManagerData();
            } else {
                toastr.error(res.data.message || 'Failed to toggle status.');
            }
        } catch (err) {
            console.error('Toggle status error', err);
            toastr.error(err.response?.data?.message || 'Failed to update status.');
        }
    }

    // BLOG / REELS SECTION SETTINGS MODAL
    function openBlogReelsSettingsModal() {
        if (!managerData || !managerData.sections) return;
        const blogSec = managerData.sections.find(s => s.id === 'blog_reels');
        const settings = (blogSec && blogSec.section_settings) || {};

        document.getElementById('blogReelsSettingsTitle').value = settings.title || 'Behind the Stitches';
        document.getElementById('blogReelsSettingsTagText').value = settings.badge || 'Studio Journal & Video Reels';
        document.getElementById('blogReelsSettingsSubtitle').value = settings.description || 'Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.';
        document.getElementById('blogReelsSettingsCtaText').value = settings.cta_text || 'Follow @knotelleindia';
        document.getElementById('blogReelsSettingsCtaLink').value = settings.cta_link || 'https://instagram.com/knotelleindia';

        const modal = document.getElementById('blogReelsSettingsModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBlogReelsSettingsModal() {
        const modal = document.getElementById('blogReelsSettingsModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function handleBlogReelsSettingsSubmit(event) {
        if (event) event.preventDefault();
        const form = document.getElementById('blogReelsSettingsForm');
        const formData = new FormData(form);

        try {
            const res = await axios.post('/admin/media/blog-reels/settings', formData);
            if (res.data.success) {
                toastr.success(res.data.message || 'Section settings updated.');
                closeBlogReelsSettingsModal();
                broadcastMediaUpdate();
                loadManagerData();
            } else {
                toastr.error(res.data.message || 'Failed to save section settings.');
            }
        } catch (err) {
            console.error('Settings save error', err);
            toastr.error(err.response?.data?.message || 'Failed to save section settings.');
        }
    }

    // Responsive Device Preview
    function openDevicePreview(imgUrl, title, path) {
        currentPreviewUrl = imgUrl;
        document.getElementById('previewModalImg').src = imgUrl;
        document.getElementById('previewModalSlotTitle').innerText = title;
        document.getElementById('previewModalSlotPath').innerText = path;
        setPreviewDevice('desktop');

        const modal = document.getElementById('devicePreviewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDevicePreviewModal() {
        const modal = document.getElementById('devicePreviewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function setPreviewDevice(device) {
        const frame = document.getElementById('previewFrame');
        const resLabel = document.getElementById('previewFrameResolution');
        const tabDesktop = document.getElementById('previewTabDesktop');
        const tabTablet = document.getElementById('previewTabTablet');
        const tabMobile = document.getElementById('previewTabMobile');

        // Reset tab classes
        [tabDesktop, tabTablet, tabMobile].forEach(t => {
            t.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-stone-500 hover:text-stone-800 flex items-center gap-1.5';
        });

        if (device === 'desktop') {
            frame.style.maxWidth = '100%';
            resLabel.innerText = 'Desktop Viewport (1920 × 1080)';
            tabDesktop.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-stone-800 shadow-xs flex items-center gap-1.5';
        } else if (device === 'tablet') {
            frame.style.maxWidth = '640px';
            resLabel.innerText = 'Tablet Viewport (768 × 1024)';
            tabTablet.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-stone-800 shadow-xs flex items-center gap-1.5';
        } else {
            frame.style.maxWidth = '375px';
            resLabel.innerText = 'Mobile Viewport (375 × 667)';
            tabMobile.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-stone-800 shadow-xs flex items-center gap-1.5';
        }
    }

    function openGenericUploadModal() {
        switchView('library');
    }

    // Library Tabulator Table Initialization
    let libraryTable = null;
    function initLibraryTable() {
        if (libraryTable) return;

        libraryTable = new Tabulator("#mediaTable", {
            ajaxURL: "/admin/media/data",
            layout: "fitColumns",
            pagination: true,
            paginationMode: "remote",
            paginationSize: 15,
            columns: [
                {
                    title: "Preview",
                    field: "url",
                    width: 70,
                    hozAlign: "center",
                    formatter: function(cell) {
                        return `<img src="${cell.getValue()}" class="w-10 h-10 rounded-lg object-cover mx-auto" onerror="this.src='/images/logo/Logo_1.png'">`;
                    }
                },
                { title: "Name / Title", field: "name", widthGrow: 2 },
                { title: "Page", field: "page", width: 100 },
                { title: "Section", field: "section", width: 120 },
                { title: "Slot", field: "slot", width: 130 },
                { title: "Device", field: "device", width: 90 },
                { title: "Size", field: "size_formatted", width: 90, hozAlign: "right" },
                {
                    title: "Status",
                    field: "is_active",
                    width: 90,
                    hozAlign: "center",
                    formatter: function(cell) {
                        return cell.getValue() ? '<span class="text-emerald-600 font-bold text-xs">Active</span>' : '<span class="text-stone-400 text-xs">Inactive</span>';
                    }
                },
                {
                    title: "Actions",
                    field: "id",
                    width: 100,
                    hozAlign: "center",
                    formatter: function(cell) {
                        const id = cell.getValue();
                        return `
                            <button onclick="deleteMedia(${id})" class="p-1.5 text-rose-600 hover:text-rose-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ]
        });

        // Search in library table
        document.getElementById('librarySearchInput').addEventListener('keyup', function(e) {
            libraryTable.setData('/admin/media/data?search=' + encodeURIComponent(this.value));
        });

        // Library file dropzone input
        document.getElementById('libraryFileInput').addEventListener('change', async function(e) {
            if (this.files && this.files.length > 0) {
                const formData = new FormData();
                for (let i = 0; i < this.files.length; i++) {
                    formData.append('files[]', this.files[i]);
                }
                formData.append('page', 'homepage');
                formData.append('section', 'unassigned');
                formData.append('slot', 'default');

                try {
                    toastr.info('Uploading media files...');
                    const res = await axios.post('/admin/media/upload', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });
                    if (res.data.success) {
                        toastr.success(res.data.message);
                        libraryTable.setData();
                        loadManagerData();
                    }
                } catch (err) {
                    toastr.error('Upload failed');
                }
            }
        });
    }

    function refreshLibraryData() {
        if (libraryTable) {
            libraryTable.setData('/admin/media/data');
            toastr.info('Media library refreshed');
        }
    }

    async function deleteMedia(id) {
        if (!confirm('Are you sure you want to delete this media file?')) return;

        try {
            const res = await axios.delete(`/admin/media/${id}`);
            if (res.data.success) {
                toastr.success(res.data.message);
                broadcastMediaUpdate();
                if (libraryTable) libraryTable.setData();
                loadManagerData();
            } else {
                toastr.error(res.data.message);
            }
        } catch (err) {
            toastr.error(err.response?.data?.message || 'Failed to delete media.');
        }
    }
</script>
@endpush
