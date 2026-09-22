@extends('admin.layouts.master')

@section('title', 'Offers & Promotions - Admin Panel')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-stone-800 mb-2">Offers Management</h2>
                <p class="text-stone-600">Manage special offers, discounts, and promotions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.offers.create') }}" class="btn-primary btn-sm whitespace-nowrap shadow-sm">
                    <i class="fas fa-plus mr-1 text-xs"></i>Create Offer
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-3.5 sm:p-6">
            <div class="flex items-center">
                <div class="p-2.5 sm:p-3 rounded-lg bg-emerald-100 text-emerald-600 mr-3 sm:mr-4 shrink-0">
                    <i class="fas fa-tags text-lg sm:text-xl"></i>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-stone-600 font-medium">Total Offers</p>
                    <h3 class="text-xl sm:text-2xl font-bold text-stone-800" id="totalOffers">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-3.5 sm:p-6">
            <div class="flex items-center">
                <div class="p-2.5 sm:p-3 rounded-lg bg-emerald-100 text-emerald-600 mr-3 sm:mr-4 shrink-0">
                    <i class="fas fa-check-circle text-lg sm:text-xl"></i>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-stone-600 font-medium">Active Offers</p>
                    <h3 class="text-xl sm:text-2xl font-bold text-stone-800" id="activeOffers">0</h3>
                </div>
            </div>
        </div>
        <div class="col-span-2 sm:col-span-1 bg-white rounded-2xl shadow-sm border border-red-100 p-3.5 sm:p-6">
            <div class="flex items-center">
                <div class="p-2.5 sm:p-3 rounded-lg bg-amber-100 text-amber-600 mr-3 sm:mr-4 shrink-0">
                    <i class="fas fa-clock text-lg sm:text-xl"></i>
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-stone-600 font-medium">Expired Offers</p>
                    <h3 class="text-xl sm:text-2xl font-bold text-stone-800" id="expiredOffers">0</h3>
                </div>
            </div>
        </div>
        <!-- <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-rose-100 text-rose-600 mr-4">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-stone-600">Most Used</p>
                    <h3 class="text-lg font-semibold text-stone-800 truncate" id="mostUsedOffer">-</h3>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar"
        class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-xl shadow-lg border border-stone-200 p-4 z-50 w-full max-w-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-indigo-600 mr-2"></i>
                    <span id="selectedCount" class="font-semibold text-gray-800">0</span>
                    <span class="text-gray-600 ml-1">offer(s) selected</span>
                </div>
                <div class="hidden sm:block border-l border-gray-300 h-6"></div>
                <div class="hidden sm:block text-sm text-gray-500">
                    Click bulk action buttons to apply to selected items
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="clearSelection"
                    class="text-sm text-gray-600 hover:text-gray-800 px-3 py-1 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times mr-1"></i> Clear
                </button>
                <div class="flex space-x-2">
                    <button id="bulkDeleteBtn"
                        class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors text-sm">
                        <i class="fas fa-trash mr-1"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Offers Table - Tabulator -->
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-stone-200 bg-stone-50/50">
            <h3 class="text-base sm:text-lg font-bold text-stone-800">All Offers</h3>
        </div>
        <div class="p-3 sm:p-6">
            <!-- Tabulator Toolbar -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <div class="order-2 sm:order-1 w-full sm:w-auto">
                    <div class="relative w-full sm:w-[260px]">
                        <input type="text" id="offersSearchInput" placeholder="Search offers..."
                            class="pl-10 pr-4 py-2 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent w-full text-stone-900 placeholder-stone-400 text-xs sm:text-sm shadow-2xs">
                        <i class="fas fa-search absolute left-3.5 top-3 text-stone-400 text-xs sm:text-sm"></i>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 order-1 sm:order-2 w-full sm:w-auto">
                    <!-- Bulk Delete Button (Small Dustbin) -->
                    <button id="tabulatorBulkDeleteBtn" class="btn-danger btn-icon-sm hidden" title="Bulk Delete" aria-label="Bulk Delete">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                    <!-- Refresh Button (Small) -->
                    <button onclick="refreshAll()" class="btn-secondary btn-sm" title="Refresh">
                        <i class="fas fa-sync-alt text-xs"></i>
                        <span>Refresh</span>
                    </button>
                    <!-- Column Visibility Button (Small) -->
                    <button id="offersColumnVisibilityBtn" class="btn-secondary btn-sm" title="Columns">
                        <i class="fas fa-columns text-xs"></i>
                        <span>Columns</span>
                    </button>
                    <!-- Print & PDF Dropdown (Clean PDF Report) -->
                    <div class="relative inline-block text-left" id="offersPrintDropdownWrapper">
                        <button type="button" onclick="toggleOffersPrintDropdown(event)" id="offersExportBtn"
                            class="btn-secondary btn-sm hover:text-red-600 hover:bg-stone-50 flex items-center gap-1.5"
                            title="Print & PDF Options">
                            <i class="fas fa-print text-xs"></i>
                            <span>Print</span>
                            <i class="fas fa-chevron-down text-[10px] ml-0.5 transition-transform duration-200" id="offersPrintChevron"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="offersPrintDropdownMenu"
                            class="hidden absolute right-0 mt-1.5 w-36 bg-white rounded-xl shadow-lg border border-stone-200 py-1 z-50 text-xs font-medium text-stone-700">
                            <button type="button" onclick="printOffersReport('print'); closeOffersPrintDropdown();"
                                class="w-full text-left px-3.5 py-2 hover:bg-stone-50 flex items-center gap-2.5 text-stone-700 transition-colors">
                                <i class="fas fa-print text-stone-500 text-xs w-4 text-center"></i>
                                <span>Print</span>
                            </button>
                            <button type="button" onclick="printOffersReport('pdf'); closeOffersPrintDropdown();"
                                class="w-full text-left px-3.5 py-2 hover:bg-red-50 hover:text-red-600 flex items-center gap-2.5 text-stone-700 transition-colors">
                                <i class="fas fa-file-pdf text-red-500 text-xs w-4 text-center"></i>
                                <span>PDF</span>
                            </button>
                        </div>
                    </div>
                    <!-- Add Offer Button -->
                    <a href="{{ route('admin.offers.create') }}" class="btn-primary btn-sm w-full sm:w-auto shadow-sm">
                        <i class="fas fa-plus mr-1 text-xs"></i>
                        <span>+ Add Offer</span>
                    </a>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                <p class="mt-2 text-stone-500">Loading offers...</p>
            </div>

            <!-- Tabulator Table -->
            <div id="offersTable"></div>

            <!-- Pagination Info -->
            <div id="paginationInfo" class="mt-4 text-sm text-stone-500 text-center"></div>
        </div>
    </div>

    <!-- Create/Edit Offer Modal -->
    <div id="offerModal" class="fixed inset-0 bg-stone-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-6 border border-red-100 w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-2xl rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-stone-800" id="modalTitle">Add New Offer</h3>
                <button onclick="closeOfferModal()" class="text-stone-400 hover:text-stone-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="offerForm" novalidate>
                <input type="hidden" id="offerId" name="id">

                <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Offer Name *</label>
                            <input type="text" id="name" name="name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <div id="nameError" class="hidden mt-1 text-sm text-rose-600"></div>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Offer Code</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" id="code" name="code"
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <button type="button" onclick="generateOfferCode()"
                                    class="btn-secondary whitespace-nowrap">
                                    Generate
                                </button>
                            </div>
                            <div id="codeError" class="hidden mt-1 text-sm text-rose-600"></div>
                            <p class="text-xs text-gray-500 mt-1">Leave empty for auto-generated code</p>
                        </div>
                    </div>

                    <!-- Banner Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Offer Banner</label>
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                <div id="bannerPreview" class="w-32 h-32 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50">
                                    <span class="text-gray-400 text-xs">No banner</span>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <input type="hidden" id="banner" name="banner">
                                <button type="button" onclick="openBannerSelector()" class="btn-secondary text-sm">
                                    <i class="fas fa-image mr-2"></i>Select/Upload Banner
                                </button>
                                <p class="text-xs text-gray-500 mt-2">Recommended size: 1200x600px. This banner will be shown as a popup on first visit.</p>
                                <div id="bannerSelectedInfo" class="hidden mt-2 flex items-center text-sm text-red-600">
                                    <i class="fas fa-check-circle mr-2"></i>Banner Selected
                                    <button type="button" onclick="removeBanner()" class="ml-4 text-rose-600 hover:text-rose-800 font-semibold">
                                        <i class="fas fa-times mr-1"></i>Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Banner Button Info -->
                    <div id="bannerButtonFields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="banner_button_text" class="block text-sm font-medium text-gray-700 mb-1">Banner Button Text</label>
                            <input type="text" id="banner_button_text" name="banner_button_text" placeholder="e.g., Shop Now"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="banner_button_link" class="block text-sm font-medium text-gray-700 mb-1">Banner Button Link</label>
                            <input type="text" id="banner_button_link" name="banner_button_link" placeholder="e.g., /products/categories/1"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>


                    <!-- Offer Type -->
                    <div>
                        <label for="offer_type" class="block text-sm font-medium text-gray-700 mb-1">Offer Type *</label>
                        <select id="offer_type" name="offer_type" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                            onchange="updateOfferFields()">
                            <option value="">Select Offer Type</option>
                            <option value="percentage">Percentage Discount</option>
                            <option value="fixed">Fixed Amount Discount</option>
                            <option value="bogo">Buy One Get One (BOGO)</option>
                            <option value="buy_x_get_y">Buy X Get Y</option>
                            <option value="free_shipping">Free Shipping</option>
                            <option value="tiered">Tiered Discount</option>
                        </select>
                        <div id="offer_typeError" class="hidden mt-1 text-sm text-rose-600"></div>
                    </div>

                    <!-- Dynamic Fields based on Offer Type -->
                    <div id="percentageFields" class="hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">
                                    Discount Percentage *
                                </label>
                                <div class="relative">
                                    <input type="number" id="discount_value" name="discount_value" min="0" max="100" step="0.01"
                                        class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <span class="absolute right-3 top-2.5 text-gray-500">%</span>
                                </div>
                            </div>
                            <div>
                                <label for="max_discount" class="block text-sm font-medium text-gray-700 mb-1">
                                    Maximum Discount Amount
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">₹</span>
                                    <input type="number" id="max_discount" name="max_discount" min="0" step="0.01"
                                        class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="fixedFields" class="hidden space-y-4">
                        <div>
                            <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">
                                Discount Amount *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">₹</span>
                                <input type="number" id="discount_value_fixed" name="discount_value" min="0" step="0.01"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div id="bogoFields" class="hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="buy_qty" class="block text-sm font-medium text-gray-700 mb-1">
                                    Buy Quantity *
                                </label>
                                <input type="number" id="buy_qty" name="buy_qty" min="1" value="1"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="get_qty" class="block text-sm font-medium text-gray-700 mb-1">
                                    Get Quantity *
                                </label>
                                <input type="number" id="get_qty" name="get_qty" min="1" value="1"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div id="buyXGetYFields" class="hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="buy_qty" class="block text-sm font-medium text-gray-700 mb-1">
                                    Buy Quantity *
                                </label>
                                <input type="number" id="buy_qty_xy" name="buy_qty" min="1" value="2"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="get_qty" class="block text-sm font-medium text-gray-700 mb-1">
                                    Get Quantity *
                                </label>
                                <input type="number" id="get_qty_xy" name="get_qty" min="1" value="1"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Cart Amount Limits -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="min_cart_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Minimum Cart Amount
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">₹</span>
                                <input type="number" id="min_cart_amount" name="min_cart_amount" min="0" step="0.01"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label for="max_cart_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Maximum Cart Amount
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500">₹</span>
                                <input type="number" id="max_cart_amount" name="max_cart_amount" min="0" step="0.01"
                                    class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Validity Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="datetime-local" id="starts_at" name="starts_at"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="datetime-local" id="ends_at" name="ends_at"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Usage Limits -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-1">
                                Maximum Total Uses
                            </label>
                            <input type="number" id="max_uses" name="max_uses" min="1"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Unlimited">
                        </div>
                        <div>
                            <label for="uses_per_customer" class="block text-sm font-medium text-gray-700 mb-1">
                                Uses Per Customer
                            </label>
                            <input type="number" id="uses_per_customer" name="uses_per_customer" min="1"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Unlimited">
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="status" name="status" checked
                                class="rounded border-gray-300 text-indigo-600">
                            <label for="status" class="text-sm text-gray-700">Active</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="is_auto_apply" name="is_auto_apply"
                                class="rounded border-gray-300 text-indigo-600">
                            <label for="is_auto_apply" class="text-sm text-gray-700">Auto Apply</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="is_stackable" name="is_stackable"
                                class="rounded border-gray-300 text-indigo-600">
                            <label for="is_stackable" class="text-sm text-gray-700">Stackable</label>
                        </div>
                         <div class="flex items-center space-x-2">
                            <input type="checkbox" id="show_at_start" name="show_at_start"
                                class="rounded border-gray-300 text-indigo-600">
                            <label for="show_at_start" class="text-sm text-gray-700">Show as Start Banner</label>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apply to Categories</label>
                        <div class="border border-gray-300 rounded-lg p-4 max-h-48 overflow-y-auto">
                            <div id="categoriesContainer" class="space-y-2">
                                <!-- Categories will be loaded dynamically -->
                                <div class="text-center py-4 text-gray-500">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading categories...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products/Variants -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apply to Specific Products</label>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-600">Select specific product variants</span>
                                <button type="button" onclick="openProductSelector()" class="btn-secondary text-sm">
                                    <i class="fas fa-plus mr-1"></i> Add Products
                                </button>
                            </div>
                            <div id="selectedVariants" class="space-y-2">
                                <!-- Selected variants will appear here -->
                            </div>
                            <input type="hidden" id="variants" name="variants">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                    <button type="button" onclick="closeOfferModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        <span id="submitText">Save Offer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Selector Modal -->
    <div id="productSelectorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-6 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Select Products</h3>
                <button onclick="closeProductSelector()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <div id="productsList" class="space-y-2">
                    <!-- Products will be loaded here -->
                </div>
            </div>
            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <button onclick="closeProductSelector()" class="btn-secondary">Cancel</button>
                <button onclick="addSelectedProducts()" class="btn-primary">Add Selected</button>
            </div>
        </div>
    </div>

    @include('admin.partials.media-modal')
@endsection

@push('styles')
    <style>
        /* Offers Tabulator Styles */
        #offersTable {
            border: none !important;
            background: transparent !important;
            min-height: 400px;
        }

        .tabulator-tableholder {
            background: transparent !important;
            border: none !important;
        }

        .tabulator .tabulator-header {
            border: none !important;
            border-bottom: 1px solid #e0f2fe !important;
            background-color: #fafaf9 !important;
            font-weight: 600;
            color: #1c1917;
        }

        .tabulator .tabulator-col {
            background-color: #fafaf9 !important;
            border-right: 1px solid #f3f4f6 !important;
            padding: 12px 8px !important;
        }

        .tabulator .tabulator-col:last-child {
            border-right: none !important;
        }

        .tabulator-row {
            border-bottom: 1px solid #f5f5f4 !important;
            transition: background-color 0.2s ease;
        }

        .tabulator-row.tabulator-selectable:hover {
            background-color: #f0f9ff !important;
        }

        .tabulator-row.tabulator-selected {
            background-color: #e0f2fe !important;
        }

        .tabulator-cell {
            padding: 12px 8px !important;
            border-right: 1px solid #f5f5f4 !important;
            vertical-align: middle !important;
        }

        .tabulator-cell:last-child {
            border-right: none !important;
        }

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
            background-color: #0ea5e9;
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

        .tabulator-footer {
            border-top: 1px solid #e0f2fe !important;
            background-color: #fafaf9 !important;
            padding: 12px !important;
        }

        /* Bulk Actions Bar Styles */
        #bulkActionsBar {
            animation: slideUp 0.3s ease-out;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @keyframes slideUp {
            from {
                transform: translate(-50%, 100%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }

        /* Loading state */
        #loadingState {
            display: none;
        }

        /* Status badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-expired {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-upcoming {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .tabulator .tabulator-col {
                padding: 6px 4px !important;
            }

            .tabulator .tabulator-col.select-checkbox,
            .tabulator-cell.select-checkbox {
                min-width: 36px !important;
                width: 36px !important;
                max-width: 36px !important;
                padding: 6px 2px !important;
            }

            .tabulator-cell {
                padding: 6px 4px !important;
            }

            .tabulator .tabulator-header-filter input {
                padding: 3px 4px !important;
                font-size: 11px !important;
            }

            #bulkActionsBar {
                width: 95%;
                padding: 12px;
            }

            #bulkActionsBar .flex {
                flex-direction: column;
                gap: 8px;
            }

            #bulkActionsBar .space-x-4 {
                justify-content: center;
                width: 100%;
            }

            #bulkActionsBar .space-x-2 {
                justify-content: center;
                width: 100%;
            }

            .mobile-swal {
                width: 95% !important;
                margin: 0 auto;
            }
        }

        /* Selection styles */
        .tabulator-row.tabulator-selected {
            background-color: #e0f2fe !important;
        }

        .tabulator-row.tabulator-selected:hover {
            background-color: #bae6fd !important;
        }

        /* Checkbox styling for select all */
        .tabulator-col.select-checkbox .tabulator-col-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .tabulator-col.select-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .tabulator-cell.select-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* Product selector */
        .variant-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }

        .variant-item:hover {
            background-color: #f3f4f6;
        }
    </style>
@endpush

@push('scripts')
    <script>
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
                    config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
                    window.location.href = '{{ route("admin.signin") }}';
                }
                return Promise.reject(error);
            }
        );

        // Global variables
        let offersTable = null;
        let isEditing = false;
        let currentPage = 1;
        let perPage = 10;
        let allCategories = [];
        let selectedVariants = new Map();
        let allProducts = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Offers module initialized');

            // Load data
            loadOffersData();
            loadStatistics();
            loadCategories();

            // Setup event listeners
            setupEventListeners();

            // Set default dates
            setDefaultDates();
        });

        // Load statistics from API
        async function loadStatistics() {
            try {
                const response = await axiosInstance.get('/offers/statistics');
                if (response.data.success) {
                    const stats = response.data.data;
                    
                    // Update stat cards
                    const totalEl = document.getElementById('totalOffers');
                    const activeEl = document.getElementById('activeOffers');
                    const expiredEl = document.getElementById('expiredOffers');
                    const mostUsedEl = document.getElementById('mostUsedOffer');
                    
                    if (totalEl) totalEl.textContent = stats.total_offers || 0;
                    if (activeEl) activeEl.textContent = stats.active_offers || 0;
                    if (expiredEl) expiredEl.textContent = stats.expired_offers || 0;
                    
                    // Update most used offer
                    if (mostUsedEl) {
                        if (stats.most_used_offer) {
                            mostUsedEl.textContent = stats.most_used_offer.name || '-';
                        } else {
                            mostUsedEl.textContent = '-';
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Refresh all data
        function refreshAll() {
            if (offersTable) {
                offersTable.setData();
            }
            loadStatistics();
            toastr.info('Data refreshed');
        }

        // Setup event listeners
        function setupEventListeners() {
            // Form submission
            const offerForm = document.getElementById('offerForm');
            if (offerForm) {
                offerForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveOffer();
                });
            }

            // Validate code on blur
            const codeInput = document.getElementById('code');
            if (codeInput) {
                codeInput.addEventListener('blur', function() {
                    validateOfferCode(this.value);
                });
            }
        }

        // Set default dates
        function setDefaultDates() {
            const now = new Date();
            const startDate = new Date(now.getTime() + (24 * 60 * 60 * 1000)); // Tomorrow
            const endDate = new Date(now.getTime() + (7 * 24 * 60 * 60 * 1000)); // Next week

            const startInput = document.getElementById('starts_at');
            const endInput = document.getElementById('ends_at');

            if (startInput) {
                startInput.value = startDate.toISOString().slice(0, 16);
            }

            if (endInput) {
                endInput.value = endDate.toISOString().slice(0, 16);
            }
        }

        // Banner selection
        function openBannerSelector() {
            if (typeof window.mediaModal !== 'undefined') {
                window.mediaModal.open({
                    mode: 'main',
                    onSelect: function(image) {
                        const bannerInput = document.getElementById('banner');
                        const bannerPreview = document.getElementById('bannerPreview');
                        const bannerInfo = document.getElementById('bannerSelectedInfo');
                        
                        if (bannerInput && bannerPreview && bannerInfo) {
                            bannerInput.value = image.url || image.path;
                            bannerPreview.innerHTML = `<img src="${image.url || image.path}" class="w-full h-full object-cover">`;
                            bannerInfo.classList.remove('hidden');
                        }
                    }
                });
            } else {
                toastr.error('Media module not found');
            }
        }

        function removeBanner() {
            const bannerInput = document.getElementById('banner');
            const bannerPreview = document.getElementById('bannerPreview');
            const bannerInfo = document.getElementById('bannerSelectedInfo');
            
            if (bannerInput && bannerPreview && bannerInfo) {
                bannerInput.value = '';
                bannerPreview.innerHTML = `<span class="text-gray-400 text-xs">No banner</span>`;
                bannerInfo.classList.add('hidden');
            }
        }

        // Generate offer code
        function generateOfferCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            for (let i = 0; i < 8; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('code').value = code;
            validateOfferCode(code);
        }

        // Validate offer code
        async function validateOfferCode(code) {
            if (!code) return;

            try {
                const response = await axiosInstance.get('/offers/validate-code', {
                    params: {
                        code: code,
                        exclude_id: isEditing ? document.getElementById('offerId').value : null
                    }
                });

                const codeError = document.getElementById('codeError');
                if (response.data.data.exists) {
                    codeError.textContent = 'This code is already in use';
                    codeError.classList.remove('hidden');
                } else {
                    codeError.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error validating code:', error);
            }
        }

        // Update offer fields based on type
        function updateOfferFields() {
            const offerType = document.getElementById('offer_type').value;

            // Hide all fields
            ['percentageFields', 'fixedFields', 'bogoFields', 'buyXGetYFields'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.classList.add('hidden');
            });

            // Show relevant fields
            switch (offerType) {
                case 'percentage':
                    document.getElementById('percentageFields').classList.remove('hidden');
                    break;
                case 'fixed':
                    document.getElementById('fixedFields').classList.remove('hidden');
                    break;
                case 'bogo':
                    document.getElementById('bogoFields').classList.remove('hidden');
                    break;
                case 'buy_x_get_y':
                    document.getElementById('buyXGetYFields').classList.remove('hidden');
                    break;
            }
        }

        // Load categories
        async function loadCategories() {
            try {
                const response = await axiosInstance.get('/categories/dropdown');
                if (response.data.success) {
                    allCategories = response.data.data;
                    renderCategories();
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        // Render categories
        function renderCategories() {
            const container = document.getElementById('categoriesContainer');
            if (!container) return;

            if (allCategories.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No categories found</p>';
                return;
            }

            let html = '';
            allCategories.forEach(category => {
                html += `
                    <div class="flex items-center">
                        <input type="checkbox" id="category_${category.id}" name="categories[]" value="${category.id}"
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="category_${category.id}" class="ml-2 text-sm text-gray-700">
                            ${category.name}
                        </label>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // Open product selector
        function openProductSelector() {
            loadProducts();
            document.getElementById('productSelectorModal').classList.remove('hidden');
        }

        // Close product selector
        function closeProductSelector() {
            document.getElementById('productSelectorModal').classList.add('hidden');
        }

        // Load products
        async function loadProducts() {
            try {
                const response = await axiosInstance.get('/products/dropdown');
                if (response.data.success) {
                    allProducts = response.data.data;
                    renderProducts();
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        // Render products
        function renderProducts() {
            const container = document.getElementById('productsList');
            if (!container) return;

            if (allProducts.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No products found</p>';
                return;
            }

            let html = '';
            allProducts.forEach(product => {
                // Check if already selected
                const isSelected = Array.from(selectedVariants.values()).some(
                    variant => variant.product_id === product.id
                );

                html += `
                    <div class="variant-item">
                        <div class="flex items-center">
                            <input type="checkbox" id="product_${product.id}"
                                value="${product.id}" class="mr-3 h-4 w-4 text-indigo-600"
                                ${isSelected ? 'checked disabled' : ''}>
                            <div>
                                <span class="font-medium">${product.name}</span>
                                <span class="text-sm text-gray-500 ml-2">SKU: ${product.sku}</span>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">₹${product.price}</span>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        // Add selected products
        function addSelectedProducts() {
            const checkboxes = document.querySelectorAll('#productsList input[type="checkbox"]:checked');
            checkboxes.forEach(checkbox => {
                const productId = parseInt(checkbox.value);
                const product = allProducts.find(p => p.id === productId);

                if (product && !selectedVariants.has(productId)) {
                    selectedVariants.set(productId, {
                        id: productId,
                        product_id: productId,
                        product_name: product.name,
                        sku: product.sku,
                        price: product.price
                    });
                }
            });

            renderSelectedVariants();
            closeProductSelector();
        }

        // Render selected variants
        function renderSelectedVariants() {
            const container = document.getElementById('selectedVariants');
            const variantsInput = document.getElementById('variants');

            if (!container) return;

            if (selectedVariants.size === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">No products selected</p>';
                variantsInput.value = '';
                return;
            }

            let html = '';
            const variantIds = [];

            selectedVariants.forEach((variant, id) => {
                html += `
                    <div class="variant-item" data-id="${id}">
                        <div>
                            <span class="font-medium">${variant.product_name}</span>
                            <span class="text-sm text-gray-500 ml-2">SKU: ${variant.sku}</span>
                        </div>
                        <button type="button" onclick="removeVariant(${id})"
                            class="text-rose-600 hover:text-rose-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                variantIds.push(id);
            });

            container.innerHTML = html;
            variantsInput.value = JSON.stringify(variantIds);
        }

        // Remove variant
        function removeVariant(variantId) {
            selectedVariants.delete(variantId);
            renderSelectedVariants();
        }

        // Initialize Tabulator table
        function initializeOffersTable(data = []) {
            offersTable = new Tabulator("#offersTable", {
                data: data,
                layout: "fitColumns",
                height: "100%",
                responsiveLayout: "hide",
                pagination: true,
                paginationSize: perPage,
                paginationSizeSelector: [10, 25, 50, 100],
                paginationCounter: "rows",
                ajaxURL: "{{ url('') }}/api/admin/offers",
                ajaxParams: {
                    sort: 'created_at',
                    direction: 'desc'
                },
                ajaxResponse: function(url, params, response) {
                    if (response.success) {
                        // Hide loading state
                        document.getElementById('loadingState').style.display = 'none';

                        // Ensure we're returning the correct data structure
                        if (response.data && response.data.data) {
                            updatePaginationInfo(response.data.meta);
                            return response.data.data;
                        }
                        return [];
                    }
                    return [];
                },
                ajaxError: function(xhr, textStatus, errorThrown) {
                    console.error('Ajax error:', xhr, textStatus, errorThrown);
                    document.getElementById('loadingState').style.display = 'none';
                    toastr.error('Failed to load offers data');
                },
                columns: [{
                        title: "<input type='checkbox' id='selectAllOffers'>",
                        field: "id",
                        formatter: "rowSelection",
                        titleFormatter: "rowSelection",
                        hozAlign: "center",
                        headerSort: false,
                        width: 45,
                        minWidth: 40,
                        cssClass: "select-checkbox",
                        responsive: 2
                    },
                    {
                        title: "ID",
                        field: "id",
                        width: 48,
                        minWidth: 45,
                        sorter: "number",
                        hozAlign: "center",
                        headerFilter: "input",
                        headerFilterPlaceholder: "ID…",
                        responsive: 0
                    },
                    {
                        title: "Offer",
                        field: "name",
                        widthGrow: 3,
                        minWidth: 160,
                        sorter: "string",
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search Offers…",
                        responsive: 0,
                        formatter: function(cell, formatterParams, onRendered) {
                            const row = cell.getRow();
                            const data = row.getData();

                            let statusBadge = '';
                            if (data.is_active || data.status === 1 || data.status === true) {
                                statusBadge = '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">Active</span>';
                            } else if (data.status === 0 || data.status === false) {
                                statusBadge = '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600 border border-stone-200 shrink-0">Inactive</span>';
                            } else if (data.days_remaining < 0) {
                                statusBadge = '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200 shrink-0">Expired</span>';
                            } else {
                                statusBadge = '<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200 shrink-0">Upcoming</span>';
                            }

                            let codeHtml = '';
                            if (data.code) {
                                codeHtml = `<span class="text-[11px] font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.2 rounded tracking-wide shrink-0">${data.code}</span>`;
                            }

                            let discountHtml = '';
                            if (data.offer_type === 'percentage') {
                                discountHtml = `<span class="text-xs font-bold text-red-600">${data.discount_value}% OFF</span>`;
                            } else if (data.offer_type === 'fixed') {
                                discountHtml = `<span class="text-xs font-bold text-red-600">₹${data.discount_value} OFF</span>`;
                            } else if (data.offer_type === 'bogo' || data.offer_type === 'buy_x_get_y') {
                                discountHtml = `<span class="text-xs font-bold text-amber-700">Buy ${data.buy_qty || 1} Get ${data.get_qty || 1}</span>`;
                            } else if (data.offer_type === 'free_shipping') {
                                discountHtml = `<span class="text-xs font-bold text-blue-700">Free Shipping</span>`;
                            }

                            return `
                                <div class="py-1 space-y-1 min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-xs sm:text-sm text-stone-900 truncate">${data.name}</span>
                                        ${codeHtml}
                                        <span class="sm:hidden">${statusBadge}</span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap text-xs">
                                        ${discountHtml}
                                        <span class="text-[11px] text-stone-400 hidden sm:inline">•</span>
                                        <span class="text-[11px] text-stone-500 hidden sm:inline">${data.offer_type_text || ''}</span>
                                        <span class="hidden sm:inline">${statusBadge}</span>
                                    </div>
                                    <div class="text-[10px] sm:text-xs text-stone-500 truncate">
                                        ${data.starts_at_formatted ? `Starts: ${data.starts_at_formatted}` : ''}
                                        ${data.ends_at_formatted ? ` | Ends: ${data.ends_at_formatted}` : (data.starts_at_formatted ? '' : 'Ongoing')}
                                        <span class="sm:hidden text-stone-400"> (${data.used_count || 0} used)</span>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Value",
                        field: "discount_value",
                        width: 110,
                        hozAlign: "center",
                        formatter: function(cell) {
                            const row = cell.getRow();
                            const data = row.getData();

                            if (data.offer_type === 'percentage') {
                                return `<span class="font-bold text-red-600">${data.discount_value}%</span>`;
                            } else if (data.offer_type === 'fixed') {
                                return `<span class="font-bold text-red-600">₹${data.discount_value}</span>`;
                            } else if (data.offer_type === 'bogo' || data.offer_type === 'buy_x_get_y') {
                                return `<span class="text-amber-600">${data.buy_qty || 1} → ${data.get_qty || 1}</span>`;
                            } else if (data.offer_type === 'free_shipping') {
                                return `<span class="text-blue-600">Free Shipping</span>`;
                            }
                            return '-';
                        },
                        responsive: 2
                    },
                    {
                        title: "Uses",
                        field: "used_count",
                        width: 90,
                        sorter: "number",
                        hozAlign: "center",
                        formatter: function(cell) {
                            const count = cell.getValue() || 0;
                            return `<div class="text-center">
                                <span class="font-semibold text-gray-900">${count}</span>
                                <div class="text-xs text-gray-500">used</div>
                            </div>`;
                        },
                        responsive: 3
                    },
                    {
                        title: "Auto Apply",
                        field: "is_auto_apply",
                        width: 110,
                        hozAlign: "center",
                        formatter: function(cell) {
                            const row = cell.getRow();
                            const data = row.getData();
                            const isAutoApply = data.is_auto_apply === true || data.is_auto_apply === 1;
                            return `
                                <label class="switch">
                                    <input type="checkbox" class="toggle-auto-apply"
                                           data-id="${data.id}" ${isAutoApply ? 'checked' : ''}>
                                    <span class="slider round"></span>
                                </label>
                            `;
                        },
                        responsive: 3
                    },
                    {
                        title: "Status",
                        field: "status",
                        width: 100,
                        hozAlign: "center",
                        formatter: function(cell) {
                            const row = cell.getRow();
                            const data = row.getData();
                            const isActive = data.status === 1 || data.status === true;
                            return `
                                <label class="switch">
                                    <input type="checkbox" class="toggle-offer-status"
                                           data-id="${data.id}" ${isActive ? 'checked' : ''}>
                                    <span class="slider round"></span>
                                </label>
                            `;
                        },
                        responsive: 2
                    },
                    {
                        title: "Created",
                        field: "created_at_formatted",
                        width: 120,
                        sorter: "date",
                        hozAlign: "center",
                        responsive: 4
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 90,
                        minWidth: 85,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const id = cell.getValue();
                            return `
                                <div class="flex space-x-1 sm:space-x-1.5 justify-center items-center">
                                    <button onclick="editOffer(${id})"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="viewOffer(${id})"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors" title="View">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="deleteOffer(${id})"
                                            class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Delete">
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

            // Fix layout after table is built
            offersTable.on("tableBuilt", function(){
                // Redraw table to ensure proper layout
                setTimeout(() => {
                    offersTable.redraw(true);
                }, 100);

                // Initialize table functionality
                initOffersSearch();
                initOffersExport();
                initOffersColumnVisibility();
                initBulkActions();

                // Add click event for select all checkbox
                $(document).on('click', '#selectAllOffers', function() {
                    if ($(this).is(':checked')) {
                        offersTable.selectRow();
                    } else {
                        offersTable.deselectRow();
                    }
                });

                // Status toggle event delegation
                $(document).on('change', '.toggle-offer-status', function(e) {
                    const offerId = $(this).data('id');
                    const isActive = $(this).is(':checked');
                    toggleOfferStatus(offerId, isActive);
                });

                // Auto-apply toggle event delegation
                $(document).on('change', '.toggle-auto-apply', function(e) {
                    const offerId = $(this).data('id');
                    const isAutoApply = $(this).is(':checked');
                    toggleAutoApply(offerId, isAutoApply);
                });
            });
        }

        // Load offers data and initialize Tabulator
        async function loadOffersData(page = 1, perPage = 10) {
            try {
                // Show loading state
                document.getElementById('loadingState').style.display = 'block';

                const response = await axiosInstance.get('/offers', {
                    params: {
                        page: page,
                        per_page: perPage,
                        sort: 'created_at',
                        direction: 'desc'
                    }
                });

                if (response.data.success) {
                    const offers = response.data.data.data || [];
                    const meta = response.data.data.meta || {};

                    // Update pagination info
                    currentPage = meta.current_page || 1;
                    perPage = meta.per_page || 10;

                    // Initialize or update Tabulator
                    if (!offersTable) {
                        initializeOffersTable(offers);
                    } else {
                        offersTable.setData(offers);
                        updatePaginationInfo(meta);
                    }

                    // Hide loading state
                    document.getElementById('loadingState').style.display = 'none';
                } else {
                    toastr.error('Failed to load offers: ' + (response.data.message || 'Unknown error'));
                    document.getElementById('loadingState').style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading offers:', error);
                toastr.error('Failed to load offers. Check console for details.');
                document.getElementById('loadingState').style.display = 'none';

                // Initialize table with empty data if error
                if (!offersTable) {
                    initializeOffersTable([]);
                }
            }
        }

        // Update pagination info
        function updatePaginationInfo(meta) {
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo && meta) {
                paginationInfo.innerHTML = `
                    Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total || 0} offers
                `;
            }
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await axiosInstance.get('/offers/statistics');

                if (response.data.success) {
                    const stats = response.data.data;
                    const totalEl = document.getElementById('totalOffers');
                    const activeEl = document.getElementById('activeOffers');
                    const expiredEl = document.getElementById('expiredOffers');
                    const mostUsedEl = document.getElementById('mostUsedOffer');

                    if (totalEl) totalEl.textContent = stats.total_offers || 0;
                    if (activeEl) activeEl.textContent = stats.active_offers || 0;
                    if (expiredEl) expiredEl.textContent = stats.expired_offers || 0;

                    if (mostUsedEl) {
                        if (stats.most_used_offer) {
                            mostUsedEl.textContent = `${stats.most_used_offer.name} (${stats.most_used_offer.usages_count} uses)`;
                        } else {
                            mostUsedEl.textContent = '-';
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // ============================
        // BULK ACTIONS SYSTEM
        // ============================

        function initBulkActions() {
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const selectAllOffers = document.getElementById('selectAllOffers');
            const clearSelection = document.getElementById('clearSelection');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const tabulatorBulkDeleteBtn = document.getElementById('tabulatorBulkDeleteBtn');

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
                                bulkActionsBar.scrollIntoView({ behavior: 'smooth', block: 'end' });
                            }, 100);
                        }
                    } else {
                        bulkActionsBar.classList.remove('flex');
                        bulkActionsBar.classList.add('hidden');
                    }
                }

                // Update select all checkbox
                const totalRows = offersTable.getDataCount();
                if (selectAllOffers) {
                    selectAllOffers.checked = selectedCountNum === totalRows && totalRows > 0;
                    selectAllOffers.indeterminate = selectedCountNum > 0 && selectedCountNum < totalRows;
                }

                // Update tabulator bulk delete button
                if (tabulatorBulkDeleteBtn) {
                    if (selectedCountNum > 0) {
                        tabulatorBulkDeleteBtn.classList.remove('hidden');
                        tabulatorBulkDeleteBtn.innerHTML = `<i class="fas fa-trash text-xs"></i><span class="ml-1 text-[11px] font-bold">(${selectedCountNum})</span>`;
                        tabulatorBulkDeleteBtn.className = 'btn-danger btn-sm';
                    } else {
                        tabulatorBulkDeleteBtn.classList.add('hidden');
                    }
                }
            }

            // Select All functionality
            if (selectAllOffers) {
                selectAllOffers.addEventListener('click', function() {
                    if (this.checked) {
                        offersTable.selectRow();
                    } else {
                        offersTable.deselectRow();
                    }
                });
            }

            // Row selection event
            offersTable.on("rowSelectionChanged", function(data, rows) {
                updateBulkActions(data.length);
            });

            // Clear selection
            if (clearSelection) {
                clearSelection.addEventListener('click', function() {
                    offersTable.deselectRow();
                    if (selectAllOffers) {
                        selectAllOffers.checked = false;
                        selectAllOffers.indeterminate = false;
                    }
                    updateBulkActions(0);
                    toastr.info('Selection cleared');
                });
            }

            // Bulk Delete Function for both buttons
            async function handleBulkDelete() {
                const selectedRows = offersTable.getSelectedRows();
                const selectedIds = selectedRows.map(row => row.getData().id);

                if (selectedIds.length === 0) {
                    toastr.warning('Please select at least one offer to delete.');
                    return;
                }

                const itemName = 'offer';
                const itemCount = selectedIds.length;

                // Get selected offers data
                const selectedOffers = selectedRows.map(row => row.getData());

                Swal.fire({
                    title: `Delete ${itemCount} ${itemName}${itemCount > 1 ? 's' : ''}?`,
                    html: `
                    <div class="text-left space-y-4">
                        <p class="text-gray-700">You are about to delete <strong>${itemCount}</strong> ${itemName}${itemCount > 1 ? 's' : ''}.</p>

                        <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                            <div class="flex items-center text-rose-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-semibold">Warning</span>
                            </div>
                            <ul class="text-sm text-rose-700 space-y-1 list-disc pl-5">
                                <li>This will remove all associated data</li>
                                <li>Active offers will become unavailable</li>
                                <li>This action cannot be undone</li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Selected offer${itemCount > 1 ? 's' : ''}:</p>
                            <div class="max-h-32 overflow-y-auto">
                                ${getSelectedOffersPreview(selectedOffers)}
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
                    confirmButtonText: `Delete ${itemCount} ${itemName}${itemCount > 1 ? 's' : ''}`,
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
                            Swal.showValidationMessage('Please confirm that you understand this action cannot be undone.');
                            return false;
                        }
                        return { ids: selectedIds };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await performBulkDelete(selectedIds, itemName);
                    }
                });
            }

            // Helper: Get selected offers preview HTML
            function getSelectedOffersPreview(selectedOffers) {
                if (selectedOffers.length === 0) return '<p class="text-sm text-gray-500">No offers selected</p>';

                return selectedOffers.map(offer => `
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div class="min-w-0 flex-1">
                        <span class="text-sm text-gray-900 truncate block">${offer.name || 'Unnamed'}</span>
                        <span class="text-xs text-gray-500">${offer.offer_type_text} • ${offer.used_count || 0} uses</span>
                    </div>
                    <span class="text-xs text-gray-500">ID: ${offer.id}</span>
                </div>
            `).join('');
            }

            // Helper: Perform bulk delete
            async function performBulkDelete(selectedIds, itemName) {
                const ids = selectedIds.map(id => parseInt(id));

                Swal.fire({
                    title: 'Deleting...',
                    text: `Please wait while we delete ${ids.length} ${itemName}${ids.length > 1 ? 's' : ''}`,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => { Swal.showLoading(); }
                });

                try {
                    const response = await axiosInstance.post('/offers/bulk-delete', { ids: ids });

                    if (response.data.success) {
                        const deletedCount = response.data.data.deleted_count;

                        // Clear selection
                        offersTable.deselectRow();
                        if (selectAllOffers) {
                            selectAllOffers.checked = false;
                            selectAllOffers.indeterminate = false;
                        }
                        if (bulkActionsBar) {
                            bulkActionsBar.classList.add('hidden');
                        }

                        // Refresh data
                        await Promise.all([
                            loadOffersData(),
                            loadStatistics()
                        ]);

                        Swal.close();
                        toastr.success(`Successfully deleted ${deletedCount} offer${deletedCount > 1 ? 's' : ''}`);

                        const remainingCount = offersTable.getDataCount();
                        if (remainingCount === 0) {
                            toastr.info('All offers have been deleted.');
                        }
                    } else {
                        Swal.close();
                        toastr.error(response.data.message || 'Failed to delete offers');
                    }
                } catch (error) {
                    Swal.close();
                    if (error.response?.status === 400) {
                        toastr.error(error.response.data.message || 'Cannot delete offers with active usage');
                    } else {
                        toastr.error('Failed to delete offers');
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

            // Keyboard shortcuts for bulk actions
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + A to select all
                if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                    e.preventDefault();
                    offersTable.selectRow();
                }

                // Escape to clear selection
                if (e.key === 'Escape') {
                    offersTable.deselectRow();
                    if (selectAllOffers) {
                        selectAllOffers.checked = false;
                        selectAllOffers.indeterminate = false;
                    }
                    updateBulkActions(0);
                }

                // Delete key to trigger bulk delete (when selection exists)
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    const selectedRows = offersTable.getSelectedRows();
                    if (selectedRows.length > 0) {
                        e.preventDefault();
                        handleBulkDelete();
                    }
                }
            });
        }

        // Search functionality
        function initOffersSearch() {
            const searchInput = document.getElementById('offersSearchInput');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value;

                    // Clear previous timeout
                    clearTimeout(searchTimeout);

                    // Set new timeout
                    searchTimeout = setTimeout(() => {
                        if (searchTerm.length >= 2 || searchTerm === '') {
                            offersTable.setFilter([
                                [{
                                        field: "name",
                                        type: "like",
                                        value: searchTerm
                                    },
                                    {
                                        field: "code",
                                        type: "like",
                                        value: searchTerm
                                    },
                                    {
                                        field: "offer_type_text",
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
        function initOffersColumnVisibility() {
            const columnVisibilityBtn = document.getElementById('offersColumnVisibilityBtn');
            if (!columnVisibilityBtn || !offersTable) return;

            const columnMenu = document.createElement('div');
            columnMenu.className =
                'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

            const columns = offersTable.getColumnDefinitions();

            columns.forEach((column, index) => {
                if (index === 0) return; // skip checkbox column

                const field = column.field;
                const columnBtn = document.createElement('button');
                columnBtn.className =
                    'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
                columnBtn.innerHTML = `
            <input type="checkbox" class="mr-2" ${offersTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;

                columnBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const col = offersTable.getColumn(field);
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

        /* Print & PDF Dropdown Handlers */
        function toggleOffersPrintDropdown(event) {
            if (event) event.stopPropagation();
            const menu = document.getElementById('offersPrintDropdownMenu');
            const chevron = document.getElementById('offersPrintChevron');
            if (!menu) return;
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        function closeOffersPrintDropdown() {
            const menu = document.getElementById('offersPrintDropdownMenu');
            const chevron = document.getElementById('offersPrintChevron');
            if (menu) menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        // Export functionality (Executive Clean PDF / Print Report)
        function initOffersExport() {
            document.addEventListener('click', function(e) {
                const wrapper = document.getElementById('offersPrintDropdownWrapper');
                if (wrapper && !wrapper.contains(e.target)) {
                    closeOffersPrintDropdown();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeOffersPrintDropdown();
                }
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    printOffersReport('pdf');
                }
            });

            // Prevent native raw window.print on this page
            window.print = function() {
                printOffersReport('pdf');
            };
        }

        async function printOffersReport(format = 'pdf') {
            let offersData = [];

            // 1. First attempt: get active filtered data from Tabulator
            if (offersTable && typeof offersTable.getData === 'function') {
                try {
                    offersData = offersTable.getData("active");
                } catch (e) {
                    console.warn('Could not get active data from Tabulator', e);
                }
                if (!offersData || offersData.length === 0) {
                    try {
                        offersData = offersTable.getData();
                    } catch (e) {}
                }
            }

            // 2. If data is still empty, fetch from API
            if (!offersData || offersData.length === 0) {
                try {
                    const searchInput = document.getElementById('offersSearchInput');
                    const searchTerm = searchInput ? searchInput.value : '';
                    const params = { per_page: 500, sort: 'created_at', direction: 'desc' };
                    if (searchTerm) params.search = searchTerm;
                    const res = await axiosInstance.get('/offers', { params: params });
                    if (res.data && res.data.success) {
                        offersData = res.data.data.data || [];
                    }
                } catch (e) {
                    console.error('Failed to fetch offers for print', e);
                }
            }

            const docTitle = format === 'pdf' ? 'KNOTELLE_Offers_Report.pdf' : 'Offers Report - KNOTELLE';
            const badgeText = format === 'pdf' ? 'Offers PDF Export' : 'Offers Report';

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            let rowsHtml = '';
            if (offersData && offersData.length > 0) {
                offersData.forEach((item, idx) => {
                    const bg = idx % 2 === 1 ? '#fafaf9' : '#ffffff';

                    // Discount & Subtitle
                    let discountText = '';
                    let valueBadge = '-';
                    if (item.offer_type === 'percentage') {
                        discountText = `${item.discount_value}% OFF`;
                        valueBadge = `<span style="font-weight: 700; color: #dc2626;">${parseFloat(item.discount_value || 0).toFixed(2)}%</span>`;
                    } else if (item.offer_type === 'fixed') {
                        discountText = `₹${item.discount_value} OFF`;
                        valueBadge = `<span style="font-weight: 700; color: #dc2626;">₹${parseFloat(item.discount_value || 0).toFixed(2)}</span>`;
                    } else if (item.offer_type === 'bogo' || item.offer_type === 'buy_x_get_y') {
                        discountText = `Buy ${item.buy_qty || 1} Get ${item.get_qty || 1}`;
                        valueBadge = `<span style="font-weight: 700; color: #b45309;">${item.buy_qty || 1} &rarr; ${item.get_qty || 1}</span>`;
                    } else if (item.offer_type === 'free_shipping') {
                        discountText = 'Free Shipping';
                        valueBadge = `<span style="font-weight: 700; color: #2563eb;">Free Shipping</span>`;
                    }

                    // Status badge
                    let statusBadge = '';
                    if (item.is_active || item.status === 1 || item.status === true) {
                        statusBadge = '<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #d1fae5; color: #065f46;">Active</span>';
                    } else if (item.status === 0 || item.status === false) {
                        statusBadge = '<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #f3f4f6; color: #4b5563;">Inactive</span>';
                    } else if (item.days_remaining < 0) {
                        statusBadge = '<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #fee2e2; color: #991b1b;">Expired</span>';
                    } else {
                        statusBadge = '<span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #fef3c7; color: #92400e;">Upcoming</span>';
                    }

                    // Validity
                    let validityDates = [];
                    if (item.starts_at_formatted) validityDates.push(`<strong>Starts:</strong> ${escapeHtml(item.starts_at_formatted)}`);
                    if (item.ends_at_formatted) validityDates.push(`<strong>Ends:</strong> ${escapeHtml(item.ends_at_formatted)}`);
                    if (validityDates.length === 0) validityDates.push('Ongoing');

                    // Auto apply
                    const isAutoApply = (item.is_auto_apply === 1 || item.is_auto_apply === true);
                    const autoApplyHtml = isAutoApply
                        ? '<span style="display: inline-block; padding: 2px 7px; border-radius: 9999px; font-size: 10px; font-weight: 700; background: #e0f2fe; color: #0369a1;">Yes</span>'
                        : '<span style="color: #64748b; font-size: 10px;">No</span>';

                    rowsHtml += `
                        <tr style="background-color: ${bg};">
                            <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; font-weight: 700; color: #475569;">#${item.id}</td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 10px;">
                                <div style="font-weight: 700; color: #0f172a; font-size: 12px;">${escapeHtml(item.name || 'Untitled Offer')}</div>
                                ${item.code ? `<div style="margin-top: 3px;"><span style="display: inline-block; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; padding: 1px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; font-family: monospace; letter-spacing: 0.5px;">${escapeHtml(item.code)}</span></div>` : ''}
                                <div style="font-size: 10px; color: #64748b; margin-top: 3px;">
                                    ${discountText ? `<strong>${discountText}</strong>` : ''}
                                    ${item.offer_type_text ? ` &bull; ${escapeHtml(item.offer_type_text)}` : ''}
                                </div>
                            </td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; font-size: 11px;">${valueBadge}</td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center;">
                                <span style="font-weight: 700; color: #0f172a; font-size: 11px;">${item.used_count || 0}</span>
                                <div style="font-size: 9px; color: #64748b;">used</div>
                            </td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center;">${autoApplyHtml}</td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center;">${statusBadge}</td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 10px; font-size: 10px; color: #334155; line-height: 1.4;">${validityDates.join('<br>')}</td>
                            <td style="border: 1px solid #e2e8f0; padding: 7px 8px; text-align: center; font-size: 10px; color: #64748b;">${escapeHtml(item.created_at_formatted || '-')}</td>
                        </tr>
                    `;
                });
            } else {
                rowsHtml = `<tr><td colspan="8" style="text-align: center; padding: 24px; color: #64748b; font-size: 12px;">No offers available</td></tr>`;
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
            frameDoc.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>${docTitle}</title>
                        <meta charset="utf-8">
                        <style>
                            @page { size: auto; margin: 12mm 10mm; }
                            body {
                                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                                margin: 0;
                                padding: 16px;
                                color: #1e293b;
                                font-size: 11px;
                                background: #ffffff;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            .header {
                                display: flex;
                                justify-content: space-between;
                                align-items: flex-start;
                                border-bottom: 2px solid #dc2626;
                                padding-bottom: 12px;
                                margin-bottom: 16px;
                            }
                            .brand {
                                display: flex;
                                align-items: flex-start;
                                gap: 12px;
                            }
                            .brand img {
                                height: 48px;
                                width: auto;
                                object-fit: contain;
                            }
                            .brand h2 {
                                margin: 0;
                                font-size: 20px;
                                color: #dc2626;
                                font-weight: 800;
                                letter-spacing: 0.5px;
                            }
                            .brand p {
                                margin: 3px 0 0 0;
                                font-size: 11px;
                                color: #475569;
                                line-height: 1.4;
                            }
                            .meta {
                                text-align: right;
                            }
                            .badge {
                                display: inline-block;
                                padding: 3px 10px;
                                font-size: 11px;
                                font-weight: 700;
                                background: #fee2e2;
                                color: #dc2626;
                                border-radius: 9999px;
                                text-transform: uppercase;
                            }
                            .meta p {
                                margin: 4px 0 0 0;
                                font-size: 10px;
                                color: #64748b;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 8px;
                                font-size: 11px;
                            }
                            th, td {
                                border: 1px solid #e2e8f0;
                                padding: 7px 10px;
                                text-align: left;
                            }
                            th {
                                background-color: #f8fafc;
                                font-weight: 700;
                                color: #475569;
                                text-transform: uppercase;
                                font-size: 10px;
                                letter-spacing: 0.3px;
                            }
                            tr:nth-child(even) {
                                background-color: #fafaf9;
                            }
                            .footer {
                                margin-top: 18px;
                                border-top: 1px solid #e2e8f0;
                                padding-top: 8px;
                                display: flex;
                                justify-content: space-between;
                                font-size: 10px;
                                color: #64748b;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <div class="brand">
                                <img src="{{ asset('images/logo/knotelle-logo.png') }}?v=2" alt="KNOTELLE">
                                <div>
                                    <h2>KNOTELLE</h2>
                                    <p>
                                        Handcrafted with Love India<br>
                                        support@knotelle.in &bull; +91 9773055555
                                    </p>
                                </div>
                            </div>
                            <div class="meta">
                                <span class="badge">${badgeText}</span>
                                <p>Generated: ${new Date().toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 45px;">ID</th>
                                    <th>OFFER</th>
                                    <th style="text-align: center; width: 100px;">VALUE</th>
                                    <th style="text-align: center; width: 70px;">USES</th>
                                    <th style="text-align: center; width: 85px;">AUTO APPLY</th>
                                    <th style="text-align: center; width: 85px;">STATUS</th>
                                    <th style="width: 170px;">VALIDITY</th>
                                    <th style="text-align: center; width: 95px;">CREATED</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rowsHtml}
                            </tbody>
                        </table>
                        <div class="footer">
                            <span>Total Offers: ${offersData ? offersData.length : 0}</span>
                            <span>KNOTELLE Admin Management System &bull; Confidential</span>
                        </div>
                    </body>
                </html>
            `);
            frameDoc.close();

            printFrame.contentWindow.focus();
            setTimeout(() => {
                printFrame.contentWindow.print();
                setTimeout(() => {
                    if (document.body.contains(printFrame)) {
                        document.body.removeChild(printFrame);
                    }
                }, 1000);
            }, 350);
        }

        // Show create offer modal
        function showCreateOfferModal() {
            isEditing = false;
            document.getElementById('modalTitle').textContent = 'Add New Offer';
            document.getElementById('submitText').textContent = 'Save Offer';
            document.getElementById('offerForm').reset();
            document.getElementById('offerId').value = '';

            // Reset dynamic fields
            updateOfferFields();

            // Reset checkboxes
            document.getElementById('status').checked = true;
            document.getElementById('is_auto_apply').checked = false;
            document.getElementById('is_stackable').checked = false;

            // Clear selected variants
            selectedVariants.clear();
            renderSelectedVariants();
            removeBanner();
            document.getElementById('show_at_start').checked = false;
            document.getElementById('banner_button_text').value = '';
            document.getElementById('banner_button_link').value = '';

            // Set default dates
            setDefaultDates();

            // Clear errors
            ['nameError', 'codeError', 'offer_typeError'].forEach(errorId => {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.textContent = '';
                }
            });

            document.getElementById('offerModal').classList.remove('hidden');
        }

        // Close offer modal
        function closeOfferModal() {
            document.getElementById('offerModal').classList.add('hidden');
        }

        // Save offer (create or update)
        async function saveOffer() {
            const form = document.getElementById('offerForm');
            
            // Build JSON payload
            const payload = {};
            
            // Basic fields
            payload.name = document.getElementById('name').value;
            payload.code = document.getElementById('code').value;
            payload.offer_type = document.getElementById('offer_type').value;
            payload.status = document.getElementById('status').checked ? 1 : 0;
            payload.is_auto_apply = document.getElementById('is_auto_apply').checked ? 1 : 0;
            payload.is_stackable = document.getElementById('is_stackable').checked ? 1 : 0;
            payload.show_at_start = document.getElementById('show_at_start').checked ? 1 : 0;
            payload.banner = document.getElementById('banner').value;
            payload.banner_button_text = document.getElementById('banner_button_text').value;
            payload.banner_button_link = document.getElementById('banner_button_link').value;
            
            // Numeric fields (only add if has value)
            const numericFields = [
                'discount_value', 'max_discount', 'buy_qty', 'get_qty', 
                'min_cart_amount', 'max_cart_amount', 'max_uses', 'uses_per_customer'
            ];
            
            numericFields.forEach(field => {
                const el = form.querySelector(`[name="${field}"]`);
                if (el && el.value !== '') {
                    payload[field] = el.value;
                }
            });
            
            // Dates
            const startsAt = document.getElementById('starts_at').value;
            const endsAt = document.getElementById('ends_at').value;
            if (startsAt) payload.starts_at = startsAt;
            if (endsAt) payload.ends_at = endsAt;
            
            // Categories (Array of IDs)
            const selectedCategories = [];
            document.querySelectorAll('input[name="categories[]"]:checked').forEach(checkbox => {
                selectedCategories.push(parseInt(checkbox.value));
            });
            if (selectedCategories.length > 0) {
                payload.categories = selectedCategories;
            }
            
            // Variants (Array of IDs)
            const variantsInput = document.getElementById('variants');
            if (variantsInput.value) {
                try {
                    payload.variants = JSON.parse(variantsInput.value);
                } catch (e) {
                    console.error('Error parsing variants', e);
                }
            }

            const method = isEditing ? 'put' : 'post';
            const offerId = document.getElementById('offerId').value;
            const url = isEditing ? `/offers/${offerId}` : '/offers';

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            // Clear previous errors
            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            try {
                const response = await axiosInstance({
                    method: method,
                    url: url,
                    data: payload,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeOfferModal();

                    // Refresh all data from APIs
                    await Promise.all([
                        loadOffersData(),
                        loadStatistics()
                    ]);
                }
            } catch (error) {
                console.error('Save offer error:', error);
                
                if (error.response?.status === 422) {
                    // Validation errors
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        // Handle array errors like 'categories.1' -> 'categoriesError' ? 
                        // Or just show first error.
                        // We map 'categories.*' to 'categoriesError' potentially?
                        // But mostly simple field names work.
                        
                        let domId = field + 'Error';
                        if (field.includes('.')) {
                             // e.g. categories.1 -> categoriesError
                             const parts = field.split('.');
                             domId = parts[0] + 'Error';
                        }
                        
                        const errorElement = document.getElementById(domId);
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                    
                    // Show the specific error message from backend if available
                    toastr.error(error.response.data.message || 'Please fix the validation errors');
                } else {
                    toastr.error(error.response?.data?.message || 'Failed to save offer');
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Edit offer
        async function editOffer(id) {
            try {
                const response = await axiosInstance.get(`/offers/${id}`);

                if (response.data.success) {
                    const offer = response.data.data;
                    isEditing = true;

                    // Fill form
                    document.getElementById('offerId').value = offer.id;
                    document.getElementById('name').value = offer.name;
                    document.getElementById('code').value = offer.code || '';
                    document.getElementById('offer_type').value = offer.offer_type;

                    // Set values based on offer type
                    if (offer.offer_type === 'percentage') {
                        document.getElementById('discount_value').value = offer.discount_value;
                        document.getElementById('max_discount').value = offer.max_discount || '';
                    } else if (offer.offer_type === 'fixed') {
                        document.getElementById('discount_value_fixed').value = offer.discount_value;
                    } else if (offer.offer_type === 'bogo' || offer.offer_type === 'buy_x_get_y') {
                        document.getElementById('buy_qty').value = offer.buy_qty || '';
                        document.getElementById('get_qty').value = offer.get_qty || '';
                    }

                    document.getElementById('min_cart_amount').value = offer.min_cart_amount || '';
                    document.getElementById('max_cart_amount').value = offer.max_cart_amount || '';
                    document.getElementById('max_uses').value = offer.max_uses || '';
                    document.getElementById('uses_per_customer').value = offer.uses_per_customer || '';

                    // Set dates
                    if (offer.starts_at) {
                        document.getElementById('starts_at').value = offer.starts_at.slice(0, 16);
                    }
                    if (offer.ends_at) {
                        document.getElementById('ends_at').value = offer.ends_at.slice(0, 16);
                    }

                    // Set checkboxes
                    document.getElementById('status').checked = offer.status === 1 || offer.status === true;
                    document.getElementById('is_auto_apply').checked = offer.is_auto_apply === 1 || offer.is_auto_apply === true;
                    document.getElementById('is_stackable').checked = offer.is_stackable === 1 || offer.is_stackable === true;
                    document.getElementById('show_at_start').checked = offer.show_at_start === 1 || offer.show_at_start === true;
                    document.getElementById('banner_button_text').value = offer.banner_button_text || '';
                    document.getElementById('banner_button_link').value = offer.banner_button_link || '';

                    // Load banner if exists
                    if (offer.banner) {
                        document.getElementById('banner').value = offer.banner;
                        document.getElementById('bannerPreview').innerHTML = `<img src="${offer.banner_url || (offer.banner.startsWith('http') ? offer.banner : '/storage/' + offer.banner)}" class="w-full h-full object-cover">`;
                        document.getElementById('bannerSelectedInfo').classList.remove('hidden');
                    } else {
                        removeBanner();
                    }

                    // Load categories
                    if (offer.categories && offer.categories.length > 0) {
                        setTimeout(() => {
                            offer.categories.forEach(category => {
                                const checkbox = document.getElementById(`category_${category.id}`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                }
                            });
                        }, 100);
                    }

                    // Load variants
                    selectedVariants.clear();
                    if (offer.variants && offer.variants.length > 0) {
                        offer.variants.forEach(variant => {
                            selectedVariants.set(variant.id, {
                                id: variant.id,
                                product_id: variant.product_id,
                                product_name: variant.product_name,
                                sku: variant.sku
                            });
                        });
                        renderSelectedVariants();
                    }

                    // Update dynamic fields
                    updateOfferFields();

                    // Update UI
                    document.getElementById('modalTitle').textContent = 'Edit Offer';
                    document.getElementById('submitText').textContent = 'Update Offer';
                    document.getElementById('offerModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error editing offer:', error);
                toastr.error('Failed to load offer details');
            }
        }

        // View offer details
        async function viewOffer(id) {
            try {
                const response = await axiosInstance.get(`/offers/${id}`);

                if (response.data.success) {
                    const offer = response.data.data;

                    let categoriesHtml = '';
                    if (offer.categories && offer.categories.length > 0) {
                        categoriesHtml = offer.categories.map(cat =>
                            `<span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1 mb-1">${cat.name}</span>`
                        ).join('');
                    } else {
                        categoriesHtml = '<span class="text-gray-500">All categories</span>';
                    }

                    let variantsHtml = '';
                    if (offer.variants && offer.variants.length > 0) {
                        variantsHtml = offer.variants.map(variant =>
                            `<div class="text-sm text-gray-600">• ${variant.product_name} (${variant.variant_name || 'Default'})</div>`
                        ).join('');
                    } else {
                        variantsHtml = '<div class="text-sm text-gray-500">All products</div>';
                    }

                    let valueHtml = '';
                    if (offer.offer_type === 'percentage') {
                        valueHtml = `${offer.discount_value}% discount`;
                        if (offer.max_discount) {
                            valueHtml += ` (max ₹${offer.max_discount})`;
                        }
                    } else if (offer.offer_type === 'fixed') {
                        valueHtml = `₹${offer.discount_value} off`;
                    } else if (offer.offer_type === 'bogo' || offer.offer_type === 'buy_x_get_y') {
                        valueHtml = `Buy ${offer.buy_qty || 1}, Get ${offer.get_qty || 1}`;
                    } else if (offer.offer_type === 'free_shipping') {
                        valueHtml = 'Free Shipping';
                    } else {
                        valueHtml = offer.offer_type_text;
                    }

                    Swal.fire({
                        title: offer.name,
                        html: `
                    <div class="text-left space-y-4">
                        <div class="flex items-center space-x-2">
                            ${offer.code ? `<span class="text-indigo-600 bg-indigo-50 px-2 py-1 rounded text-sm">${offer.code}</span>` : ''}
                            <span class="capitalize ${offer.is_active ? 'text-red-600' : 'text-rose-600'}">
                                ${offer.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div><strong>Type:</strong> ${offer.offer_type_text}</div>
                            <div><strong>Value:</strong> ${valueHtml}</div>
                            ${offer.min_cart_amount ? `<div><strong>Minimum Cart:</strong> ₹${offer.min_cart_amount}</div>` : ''}
                            ${offer.max_cart_amount ? `<div><strong>Maximum Cart:</strong> ₹${offer.max_cart_amount}</div>` : ''}
                        </div>

                        <div class="space-y-2">
                            <div><strong>Categories:</strong></div>
                            <div>${categoriesHtml}</div>
                        </div>

                        <div class="space-y-2">
                            <div><strong>Specific Products:</strong></div>
                            <div>${variantsHtml}</div>
                        </div>

                        <div class="space-y-2">
                            <div><strong>Usage:</strong> ${offer.used_count} times used</div>
                            ${offer.max_uses ? `<div><strong>Maximum Uses:</strong> ${offer.max_uses}</div>` : ''}
                            ${offer.uses_per_customer ? `<div><strong>Uses Per Customer:</strong> ${offer.uses_per_customer}</div>` : ''}
                        </div>

                        <div class="space-y-2">
                            <div><strong>Validity:</strong></div>
                            <div>${offer.starts_at_formatted ? `Starts: ${offer.starts_at_formatted}` : 'Starts immediately'}</div>
                            <div>${offer.ends_at_formatted ? `Ends: ${offer.ends_at_formatted}` : 'No end date'}</div>
                            ${offer.days_remaining > 0 ? `<div class="text-red-600">${offer.days_remaining} days remaining</div>` : ''}
                        </div>

                        <div class="space-y-2">
                            <div><strong>Settings:</strong></div>
                            <div>Auto Apply: ${offer.is_auto_apply ? 'Yes' : 'No'}</div>
                            <div>Stackable: ${offer.is_stackable ? 'Yes' : 'No'}</div>
                            <div>Exclusive: ${offer.is_exclusive ? 'Yes' : 'No'}</div>
                        </div>

                        <div class="text-sm text-gray-500">
                            Created: ${offer.created_at_formatted}
                        </div>
                    </div>
                `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Edit',
                        cancelButtonText: 'Close',
                        reverseButtons: true,
                        width: '600px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            editOffer(id);
                        }
                    });
                }
            } catch (error) {
                toastr.error('Failed to load offer details');
            }
        }

        // Delete offer
        async function deleteOffer(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the offer and all associated data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.delete(`/offers/${id}`);

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        // Refresh all data from APIs
                        await Promise.all([
                            loadOffersData(),
                            loadStatistics()
                        ]);
                    } else {
                        toastr.error(response.data.message || 'Failed to delete offer');
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Failed to delete offer');
                }
            }
        }

        // Toggle offer status
        async function toggleOfferStatus(id, isActive) {
            const result = await Swal.fire({
                title: 'Confirm Status Change',
                text: `Are you sure you want to ${isActive ? 'activate' : 'deactivate'} this offer?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${isActive ? 'activate' : 'deactivate'}`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: isActive ? '#10b981' : '#ef4444'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(`/offers/${id}/status`, {
                        status: isActive ? 1 : 0
                    });

                    if (response.data.success) {
                        toastr.success(`Offer ${isActive ? 'activated' : 'deactivated'} successfully!`);
                        loadOffersData();
                    } else {
                        toastr.error('Failed to update offer status');
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Failed to update offer status');
                }
            } else {
                // Revert the switch by reloading data
                loadOffersData();
            }
        }

        // Toggle auto apply
        async function toggleAutoApply(id, isAutoApply) {
            const result = await Swal.fire({
                title: 'Update Auto Apply',
                text: `Are you sure you want to ${isAutoApply ? 'enable' : 'disable'} auto apply?`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: `Yes, ${isAutoApply ? 'enable' : 'disable'}`,
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(`/offers/${id}/auto-apply`, {
                        is_auto_apply: isAutoApply ? 1 : 0
                    });

                    if (response.data.success) {
                        toastr.success(`Auto apply ${isAutoApply ? 'enabled' : 'disabled'} successfully!`);
                        loadOffersData();
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Failed to update auto apply');
                }
            }
        }

        // Refresh all data
        async function refreshAll() {
            try {
                await Promise.all([
                    loadOffersData(),
                    loadStatistics()
                ]);
                toastr.info('Data refreshed');
            } catch (error) {
                toastr.error('Failed to refresh data');
            }
        }
    </script>
@endpush
