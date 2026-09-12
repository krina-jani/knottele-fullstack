@extends('admin.layouts.master')

@section('title', 'Products')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Products</h2>
            <p class="text-stone-600">Manage your store inventory and variants</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary flex items-center shadow-lg shadow-red-100">
            <i class="fas fa-plus mr-2"></i>
            Add Product
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm p-5 border border-stone-100 mb-8">
    <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="col-span-1 md:col-span-2 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU..." 
                class="w-full pl-11 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-stone-900 placeholder-stone-400">
            <i class="fas fa-search absolute left-4 top-3 text-stone-400"></i>
        </div>
        <div>
            <select name="status" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-xl outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-stone-900">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn-secondary w-full py-2.5 justify-center">
                <i class="fas fa-filter mr-2 text-xs"></i>Filter
            </button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-stone-200">
            <thead class="bg-stone-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Product Info</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider text-center">Inventory</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-stone-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-stone-100">
                @forelse($products as $product)
                <tr class="hover:bg-stone-50/80 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-12 w-12 flex-shrink-0">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded-lg shadow-sm border border-stone-200">
                                @else
                                    <div class="h-12 w-12 bg-stone-100 rounded-lg flex items-center justify-center text-stone-400 text-[10px] shadow-sm border border-stone-200 font-medium">NO IMG</div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-stone-800">{{ $product->name }}</div>
                                <div class="text-xs text-stone-500 uppercase tracking-wider mt-0.5">{{ $product->product_type }}</div>
                                @if($product->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 mt-1 uppercase">Featured</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-600 font-medium">
                        {{ $product->sku ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-stone-800 font-bold">
                        ₹{{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-lg {{ $product->stock_quantity > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-rose-700' }}">
                            {{ $product->stock_quantity }} in stock
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                         <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase tracking-widest {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-600' }}">
                            {{ $product->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                               class="p-2 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-stone-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-4xl text-stone-200 mb-4"></i>
                            <p class="font-medium text-stone-600">No products found</p>
                            <p class="text-sm text-stone-400 mt-1">Start by adding your first product to the store</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
