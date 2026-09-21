@extends('admin.layouts.master')

@section('title', 'Custom Crochet Orders')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                    <i class="fas fa-wand-magic-sparkles mr-1"></i>Bespoke Atelier
                </span>
                <span class="text-xs text-stone-400 font-medium">● Realtime Sync</span>
            </div>
            <h1 class="text-2xl font-bold text-stone-800">Custom Order Requests</h1>
            <p class="text-xs text-stone-500 font-medium mt-0.5">Manage customer custom crochet commissions, yarn palettes, artisan quotes, and crafting statuses.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.custom_orders.index') }}" class="btn-secondary text-xs px-4 py-2.5 flex items-center gap-2 cursor-pointer">
                <i class="fas fa-sync-alt text-xs"></i>
                <span>Refresh List</span>
            </a>
        </div>
    </div>

    <!-- Metric KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <a href="{{ route('admin.custom_orders.index', ['status' => 'all']) }}" 
           class="p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer {{ $status === 'all' ? 'bg-red-50/50 border-red-200 ring-2 ring-red-500/20' : 'bg-white border-stone-200/80 hover:border-stone-300' }}">
            <div class="flex items-center justify-between text-stone-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Total</span>
                <i class="fas fa-layer-group text-sm"></i>
            </div>
            <div class="text-2xl font-bold text-stone-800">{{ $metrics['total'] ?? 0 }}</div>
            <span class="text-[10px] text-stone-400 font-medium">All submissions</span>
        </a>

        <a href="{{ route('admin.custom_orders.index', ['status' => 'pending']) }}" 
           class="p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer {{ $status === 'pending' ? 'bg-amber-50/50 border-amber-200 ring-2 ring-amber-500/20' : 'bg-white border-stone-200/80 hover:border-stone-300' }}">
            <div class="flex items-center justify-between text-amber-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Pending</span>
                <i class="fas fa-hourglass-start text-sm"></i>
            </div>
            <div class="text-2xl font-bold text-amber-700">{{ $metrics['pending'] ?? 0 }}</div>
            <span class="text-[10px] text-amber-600 font-medium">Awaiting review</span>
        </a>

        <a href="{{ route('admin.custom_orders.index', ['status' => 'in_review']) }}" 
           class="p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer {{ $status === 'in_review' ? 'bg-blue-50/50 border-blue-200 ring-2 ring-blue-500/20' : 'bg-white border-stone-200/80 hover:border-stone-300' }}">
            <div class="flex items-center justify-between text-blue-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Reviewing</span>
                <i class="fas fa-search text-sm"></i>
            </div>
            <div class="text-2xl font-bold text-blue-700">{{ $metrics['in_review'] ?? 0 }}</div>
            <span class="text-[10px] text-blue-600 font-medium">In evaluation</span>
        </a>

        <a href="{{ route('admin.custom_orders.index', ['status' => 'quoted']) }}" 
           class="p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer {{ $status === 'quoted' ? 'bg-purple-50/50 border-purple-200 ring-2 ring-purple-500/20' : 'bg-white border-stone-200/80 hover:border-stone-300' }}">
            <div class="flex items-center justify-between text-purple-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Quoted</span>
                <i class="fas fa-file-invoice-dollar text-sm"></i>
            </div>
            <div class="text-2xl font-bold text-purple-700">{{ $metrics['quoted'] ?? 0 }}</div>
            <span class="text-[10px] text-purple-600 font-medium">Quote issued</span>
        </a>

        <a href="{{ route('admin.custom_orders.index', ['status' => 'in_progress']) }}" 
           class="p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer {{ $status === 'in_progress' ? 'bg-rose-50/50 border-rose-200 ring-2 ring-rose-500/20' : 'bg-white border-stone-200/80 hover:border-stone-300' }}">
            <div class="flex items-center justify-between text-rose-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Crafting</span>
                <i class="fas fa-yarn text-sm"></i>
            </div>
            <div class="text-2xl font-bold text-rose-700">{{ $metrics['in_progress'] ?? 0 }}</div>
            <span class="text-[10px] text-rose-600 font-medium">On the hook</span>
        </a>

        <a href="{{ route('admin.custom_orders.index', ['status' => 'completed']) }}" 
           class="p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer {{ $status === 'completed' ? 'bg-emerald-50/50 border-emerald-200 ring-2 ring-emerald-500/20' : 'bg-white border-stone-200/80 hover:border-stone-300' }}">
            <div class="flex items-center justify-between text-emerald-500 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Completed</span>
                <i class="fas fa-check-circle text-sm"></i>
            </div>
            <div class="text-2xl font-bold text-emerald-700">{{ $metrics['completed'] ?? 0 }}</div>
            <span class="text-[10px] text-emerald-600 font-medium">Crafted & Sent</span>
        </a>
    </div>

    <!-- Search & Filter Tabs -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-stone-200/80 shadow-2xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <form action="{{ route('admin.custom_orders.index') }}" method="GET" class="flex-1 max-w-md">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}" 
                           placeholder="Search reference #, customer name, email, phone, category..."
                           class="w-full bg-stone-50 border border-stone-200 rounded-xl pl-9 pr-20 py-2 text-xs text-stone-800 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all">
                    @if($search)
                        <a href="{{ route('admin.custom_orders.index', ['status' => $status]) }}" class="absolute right-12 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600 text-xs">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                    <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2.5 py-1 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-lg text-[10px] font-bold transition-all">
                        Search
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                @php
                    $statusTabs = [
                        'all' => 'All',
                        'pending' => 'Pending',
                        'in_review' => 'Reviewing',
                        'quoted' => 'Quoted',
                        'approved' => 'Approved',
                        'in_progress' => 'Crafting',
                        'completed' => 'Completed',
                        'rejected' => 'Declined',
                    ];
                @endphp
                @foreach($statusTabs as $tabKey => $tabLabel)
                    <a href="{{ route('admin.custom_orders.index', array_merge(request()->all(), ['status' => $tabKey, 'page' => 1])) }}"
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ $status === $tabKey ? 'bg-red-600 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                        {{ $tabLabel }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Table of Requests -->
    <div class="bg-white rounded-3xl border border-stone-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/80 border-b border-stone-200/80 text-[11px] font-bold text-stone-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-6">Request Ref & Date</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Category & Size</th>
                        <th class="py-3.5 px-4">Palette & Swatches</th>
                        <th class="py-3.5 px-4">Urgency & Budget</th>
                        <th class="py-3.5 px-4 text-center">Reference Image</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Quote</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs text-stone-700 font-medium">
                    @forelse($requests as $req)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <!-- Ref & Date -->
                            <td class="py-4 px-4 sm:px-6">
                                <span class="font-mono font-bold text-red-700 bg-red-50 border border-red-200/60 px-2 py-0.5 rounded-md text-[11px] block w-max">
                                    {{ $req->reference_id }}
                                </span>
                                <span class="text-[10px] text-stone-400 mt-1 block">
                                    {{ $req->created_at ? $req->created_at->format('M d, Y • h:i A') : 'N/A' }}
                                </span>
                            </td>

                            <!-- Customer -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-stone-800">{{ $req->customer_name }}</div>
                                <div class="text-stone-500 text-[11px] font-mono truncate max-w-[150px]">{{ $req->customer_email }}</div>
                                @if($req->customer_phone)
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-stone-400 text-[10px] font-mono">{{ $req->customer_phone }}</span>
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $req->customer_phone);
                                            if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                                        @endphp
                                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello ' . $req->customer_name . ', regarding your Knotelle Custom Crochet Order #' . $req->reference_id . '...') }}" 
                                           target="_blank" title="Chat on WhatsApp"
                                           class="text-emerald-600 hover:text-emerald-700 text-[11px]">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                @endif
                            </td>

                            <!-- Category & Size -->
                            <td class="py-4 px-4">
                                <span class="font-bold text-stone-800 block">{{ $req->category }}</span>
                                <span class="text-stone-400 text-[11px] block">{{ $req->size_preference ?: 'Standard' }}</span>
                            </td>

                            <!-- Palette & Colors -->
                            <td class="py-4 px-4">
                                <div class="text-[11px] font-bold text-stone-700 mb-1">{{ $req->selected_palette ?: 'Custom Palette' }}</div>
                                @if(!empty($req->custom_colors) && is_array($req->custom_colors))
                                    <div class="flex items-center gap-1 flex-wrap max-w-[130px]">
                                        @foreach($req->custom_colors as $c)
                                            <span class="w-4 h-4 rounded-full border border-stone-300 shadow-2xs inline-block shrink-0" 
                                                  style="background-color: {{ $c }};" title="{{ $c }}"></span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <!-- Urgency & Budget -->
                            <td class="py-4 px-4">
                                <div class="text-stone-800 font-semibold text-[11px]">{{ $req->budget_range ?: 'Flexible' }}</div>
                                <span class="text-stone-400 text-[10px]">{{ $req->urgency ?: 'Standard' }}</span>
                            </td>

                            <!-- Reference Image -->
                            <td class="py-4 px-4 text-center">
                                @if($req->reference_image_url)
                                    <div class="relative inline-block group">
                                        <img src="{{ $req->reference_image_url }}" alt="Ref" class="w-10 h-10 rounded-xl object-cover border border-stone-200 cursor-pointer shadow-2xs hover:scale-105 transition-transform" onclick="openImageModal('{{ $req->reference_image_url }}', '{{ $req->reference_id }}')">
                                    </div>
                                @else
                                    <span class="text-[10px] text-stone-300 italic">No image</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border inline-block {{ $req->status_badge_class }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>

                            <!-- Quoted Price -->
                            <td class="py-4 px-4">
                                @if($req->quoted_price)
                                    <span class="font-bold text-stone-800 font-mono">₹{{ number_format($req->quoted_price, 2) }}</span>
                                @else
                                    <span class="text-stone-400 text-[11px] italic">Unquoted</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick="openOrderModal({{ $req->id }})" 
                                            class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer"
                                            title="View details and update quote">
                                        <i class="fas fa-sliders-h text-[10px]"></i>
                                        <span>Review</span>
                                    </button>
                                    <form action="{{ route('admin.custom_orders.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this custom order request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-xl text-stone-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors cursor-pointer" title="Delete request">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-stone-400">
                                <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-300 flex items-center justify-center mx-auto mb-3 text-2xl">
                                    <i class="fas fa-wand-magic-sparkles"></i>
                                </div>
                                <p class="text-sm font-bold text-stone-600">No Custom Order Requests Found</p>
                                <p class="text-xs text-stone-400 mt-1">When customers submit custom crochet requests from the website, they will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-4 border-t border-stone-100 flex items-center justify-between">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- DETAILED REVIEW & QUOTE MODAL -->
<div id="customOrderDetailsModal" class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs hidden items-center justify-center z-[9999] p-4" style="display: none;" onclick="if(event.target === this) closeOrderModal()">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[92vh] flex flex-col overflow-hidden shadow-2xl border border-stone-100 animate-fadeIn" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="p-5 px-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/80 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 font-bold shadow-2xs">
                    <i class="fas fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-stone-800">Custom Order Specifications</h3>
                        <span id="modalRefBadge" class="font-mono font-bold text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-md"></span>
                    </div>
                    <p id="modalDate" class="text-xs text-stone-400 font-medium"></p>
                </div>
            </div>
            <button type="button" onclick="closeOrderModal()" class="w-8 h-8 rounded-full bg-stone-100 text-stone-400 hover:text-stone-700 flex items-center justify-center transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6 overflow-y-auto flex-1 overscroll-contain">
            <!-- Customer Info & Quick Contact -->
            <div class="bg-stone-50/80 rounded-2xl p-4 border border-stone-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1">Customer Details</div>
                    <h4 id="modalCustomerName" class="font-bold text-base text-stone-800"></h4>
                    <div id="modalCustomerEmail" class="text-xs text-stone-600 font-mono"></div>
                    <div id="modalCustomerPhone" class="text-xs text-stone-600 font-mono mt-0.5"></div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a id="modalWhatsAppBtn" href="#" target="_blank" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-2xs transition-all">
                        <i class="fab fa-whatsapp text-sm"></i>
                        <span>Chat on WhatsApp</span>
                    </a>
                    <a id="modalEmailBtn" href="#" class="px-3 py-2 rounded-xl bg-stone-200 hover:bg-stone-300 text-stone-700 text-xs font-bold flex items-center gap-1.5 transition-all">
                        <i class="fas fa-envelope text-xs"></i>
                        <span>Email</span>
                    </a>
                </div>
            </div>

            <!-- Commission Specs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-4 border border-stone-200 space-y-3">
                    <div>
                        <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">Category & Size</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="modalCategory" class="font-bold text-stone-800 text-sm"></span>
                            <span class="text-stone-300">•</span>
                            <span id="modalSize" class="text-xs text-stone-600"></span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">Budget & Timeline</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span id="modalBudget" class="font-bold text-stone-800 text-xs"></span>
                            <span class="text-stone-300">•</span>
                            <span id="modalUrgency" class="text-xs text-stone-600"></span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 border border-stone-200 space-y-2">
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">Selected Palette & Colors</span>
                    <div id="modalPaletteName" class="font-bold text-stone-800 text-xs"></div>
                    <div id="modalSwatchesContainer" class="flex items-center gap-2 flex-wrap pt-1"></div>
                    <div id="modalColorNotes" class="text-xs text-stone-500 italic mt-1"></div>
                </div>
            </div>

            <!-- Personalization & Design Notes -->
            <div class="space-y-3">
                <div class="bg-stone-50/80 rounded-2xl p-4 border border-stone-200/80">
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block mb-1">
                        <i class="fas fa-signature mr-1 text-red-600"></i>Personalization / Initials / Monogram
                    </span>
                    <p id="modalPersonalization" class="text-xs text-stone-700 whitespace-pre-wrap font-medium"></p>
                </div>

                <div class="bg-stone-50/80 rounded-2xl p-4 border border-stone-200/80">
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block mb-1">
                        <i class="fas fa-pencil-ruler mr-1 text-red-600"></i>Detailed Design Instructions & Notes
                    </span>
                    <p id="modalDesignNotes" class="text-xs text-stone-700 whitespace-pre-wrap font-medium"></p>
                </div>
            </div>

            <!-- Reference Image Display -->
            <div id="modalImageSection" class="bg-stone-50/80 rounded-2xl p-4 border border-stone-200/80 space-y-2">
                <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block">
                    <i class="fas fa-image mr-1 text-red-600"></i>Uploaded Reference Artwork / Photo
                </span>
                <div class="relative max-w-sm rounded-xl overflow-hidden border border-stone-200 group bg-white">
                    <img id="modalReferenceImg" src="" alt="Reference Image" class="w-full h-48 object-contain">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                        <a id="modalImageOpenBtn" href="#" target="_blank" class="px-3 py-1.5 rounded-lg bg-white text-stone-800 text-xs font-bold shadow-sm">
                            <i class="fas fa-external-link-alt mr-1"></i>Open Full Size
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status, Quote & Artisan Notes Form -->
            <form id="updateOrderForm" onsubmit="handleOrderFormSubmit(event)" class="bg-red-50/30 rounded-2xl p-5 border border-red-200/60 space-y-4">
                <h4 class="font-bold text-xs uppercase tracking-wider text-stone-700 flex items-center gap-1.5">
                    <i class="fas fa-stamp text-red-600"></i>Update Status & Artisan Quote
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-stone-600 mb-1">Commission Status</label>
                        <select id="modalStatusSelect" name="status" class="w-full bg-white border border-stone-200 rounded-xl px-3 py-2 text-xs font-bold text-stone-800 focus:ring-2 focus:ring-red-500">
                            <option value="pending">Pending Review</option>
                            <option value="in_review">Artisan Reviewing</option>
                            <option value="quoted">Quote Provided</option>
                            <option value="approved">Approved / Ready to Craft</option>
                            <option value="in_progress">In Crafting</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Declined</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-stone-600 mb-1">Quoted Price (₹ INR)</label>
                        <input type="number" step="0.01" id="modalQuotedPrice" name="quoted_price" placeholder="e.g. 1500" class="w-full bg-white border border-stone-200 rounded-xl px-3 py-2 text-xs font-mono font-bold text-stone-800 focus:ring-2 focus:ring-red-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-stone-600 mb-1">Artisan / Internal Notes</label>
                    <textarea id="modalAdminNotes" name="admin_notes" rows="3" placeholder="Add yarn requirements, timeline estimates, artisan notes..." class="w-full bg-white border border-stone-200 rounded-xl p-3 text-xs text-stone-800 focus:ring-2 focus:ring-red-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="submit" id="modalSaveBtn" class="btn-primary text-xs px-6 py-2.5 flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="fas fa-save"></i>
                        <span>Save & Update Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SIMPLE FULL-SIZE IMAGE PREVIEW MODAL -->
<div id="imagePreviewModal" class="fixed inset-0 bg-stone-950/80 backdrop-blur-md hidden items-center justify-center z-[10000] p-4" style="display: none;" onclick="this.style.setProperty('display', 'none', 'important')">
    <div class="relative max-w-2xl max-h-[85vh] bg-white rounded-2xl overflow-hidden p-2 shadow-2xl" onclick="event.stopPropagation()">
        <button type="button" onclick="document.getElementById('imagePreviewModal').style.setProperty('display', 'none', 'important')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition-colors z-10">
            <i class="fas fa-times text-sm"></i>
        </button>
        <img id="fullSizeImg" src="" alt="Artwork Preview" class="max-h-[80vh] w-auto mx-auto object-contain rounded-xl">
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeOrderId = null;

    async function openOrderModal(id) {
        activeOrderId = id;
        const modal = document.getElementById('customOrderDetailsModal');
        if (!modal) return;

        modal.style.setProperty('display', 'flex', 'important');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        try {
            const res = await axios.get(`{{ url('admin/custom-orders') }}/${id}`);
            if (res.data && res.data.success) {
                const o = res.data.data;
                document.getElementById('modalRefBadge').textContent = o.reference_id;
                document.getElementById('modalDate').textContent = o.created_at ? new Date(o.created_at).toLocaleString() : '';

                document.getElementById('modalCustomerName').textContent = o.customer_name || 'Anonymous Customer';
                document.getElementById('modalCustomerEmail').textContent = o.customer_email || 'No email';
                document.getElementById('modalCustomerPhone').textContent = o.customer_phone ? `Phone: ${o.customer_phone}` : 'No phone provided';

                // WhatsApp link
                const cleanPhone = (o.customer_phone || '').replace(/[^0-9]/g, '');
                const waPhone = cleanPhone.length === 10 ? '91' + cleanPhone : cleanPhone;
                const waText = encodeURIComponent(`Hello ${o.customer_name}, regarding your Knotelle Custom Crochet Order #${o.reference_id}...`);
                const waBtn = document.getElementById('modalWhatsAppBtn');
                if (waPhone) {
                    waBtn.href = `https://wa.me/${waPhone}?text=${waText}`;
                    waBtn.style.display = 'inline-flex';
                } else {
                    waBtn.style.display = 'none';
                }

                // Email link
                const emailBtn = document.getElementById('modalEmailBtn');
                emailBtn.href = `mailto:${o.customer_email}?subject=Knotelle Custom Order #${o.reference_id}`;

                // Specs
                document.getElementById('modalCategory').textContent = o.category || 'Bouquet';
                document.getElementById('modalSize').textContent = o.size_preference ? `Size: ${o.size_preference}` : 'Standard size';
                document.getElementById('modalBudget').textContent = o.budget_range ? `Budget: ${o.budget_range}` : 'Budget: Flexible';
                document.getElementById('modalUrgency').textContent = o.urgency ? `Urgency: ${o.urgency}` : 'Standard timeline';

                // Palette & Swatches
                document.getElementById('modalPaletteName').textContent = o.selected_palette || 'Custom Color Palette';
                const swatchesContainer = document.getElementById('modalSwatchesContainer');
                swatchesContainer.innerHTML = '';
                if (o.custom_colors && Array.isArray(o.custom_colors) && o.custom_colors.length > 0) {
                    o.custom_colors.forEach(hex => {
                        const circle = document.createElement('span');
                        circle.className = 'w-6 h-6 rounded-full border border-stone-300 shadow-xs inline-block flex items-center justify-center text-[9px] font-mono text-white drop-shadow';
                        circle.style.backgroundColor = hex;
                        circle.title = hex;
                        swatchesContainer.appendChild(circle);
                    });
                } else {
                    swatchesContainer.innerHTML = '<span class="text-xs text-stone-400 italic">No specific swatches selected</span>';
                }
                document.getElementById('modalColorNotes').textContent = o.custom_color_notes ? `Note: "${o.custom_color_notes}"` : '';

                // Personalization & Notes
                document.getElementById('modalPersonalization').textContent = o.personalization || 'None requested';
                document.getElementById('modalDesignNotes').textContent = o.design_notes || 'No extra notes provided';

                // Image
                const imgSec = document.getElementById('modalImageSection');
                if (o.reference_image_url) {
                    imgSec.style.display = 'block';
                    document.getElementById('modalReferenceImg').src = o.reference_image_url;
                    document.getElementById('modalImageOpenBtn').href = o.reference_image_url;
                } else {
                    imgSec.style.display = 'none';
                }

                // Form fields
                document.getElementById('modalStatusSelect').value = o.status || 'pending';
                document.getElementById('modalQuotedPrice').value = o.quoted_price || '';
                document.getElementById('modalAdminNotes').value = o.admin_notes || '';
            }
        } catch (err) {
            toastr.error('Failed to load order details');
        }
    }

    function closeOrderModal() {
        const modal = document.getElementById('customOrderDetailsModal');
        if (modal) {
            modal.style.setProperty('display', 'none', 'important');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    async function handleOrderFormSubmit(e) {
        e.preventDefault();
        if (!activeOrderId) return;

        const btn = document.getElementById('modalSaveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        const status = document.getElementById('modalStatusSelect').value;
        const quotedPrice = document.getElementById('modalQuotedPrice').value;
        const adminNotes = document.getElementById('modalAdminNotes').value;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const res = await axios.post(`{{ url('admin/custom-orders') }}/${activeOrderId}/update-status`, {
                status: status,
                quoted_price: quotedPrice,
                admin_notes: adminNotes,
                _token: csrfToken
            });
            if (res.data && res.data.success) {
                toastr.success(res.data.message || 'Updated successfully!');
                closeOrderModal();
                setTimeout(() => window.location.reload(), 600);
            } else {
                toastr.error(res.data?.message || 'Update failed');
            }
        } catch (err) {
            toastr.error(err.response?.data?.message || 'Error updating request');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i><span>Save & Update Request</span>';
        }
    }

    function openImageModal(url, ref) {
        const modal = document.getElementById('imagePreviewModal');
        const img = document.getElementById('fullSizeImg');
        if (modal && img) {
            img.src = url;
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    window.openOrderModal = openOrderModal;
    window.closeOrderModal = closeOrderModal;
    window.openImageModal = openImageModal;
    window.handleOrderFormSubmit = handleOrderFormSubmit;
</script>
@endpush
