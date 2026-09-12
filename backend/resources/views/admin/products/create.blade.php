@extends('admin.layouts.master')

@section('title', 'Create Product')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-stone-800 mb-2">Create Product</h2>
            <nav class="text-sm text-stone-500 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition-colors">Dashboard</a>
                <span class="mx-2 text-stone-300">/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-red-600 transition-colors">Products</a>
                <span class="mx-2 text-stone-300">/</span>
                <span class="text-stone-800 font-semibold">Create</span>
            </nav>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" id="product-form" class="space-y-6" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 pb-2 border-b">Basic Information</h3>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-stone-700 mb-1">Product Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
                            placeholder="Enter product name">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="slug" class="block text-sm font-medium text-stone-700 mb-1">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition bg-stone-50 text-stone-500">
                            @error('slug') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="product_code" class="block text-sm font-medium text-stone-700 mb-1">Product Code (Art. No.)</label>
                            <input type="text" name="product_code" id="product_code" value="{{ old('product_code') }}"
                                class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                            @error('product_code') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 pb-2 border-b">Description</h3>

                <div class="space-y-4">
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-stone-700 mb-1">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="3"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">{{ old('short_description') }}</textarea>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-stone-700 mb-1">Full Description</label>
                        <textarea name="description" id="description" rows="6"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Media -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-stone-100" id="media-section">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 pb-2 border-b">Product Images</h3>

                <!-- Main Image -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-stone-700 mb-2">Main Image</label>
                    <input type="hidden" name="main_image_id" id="main_image_id" value="{{ old('main_image_id') }}">

                    <div id="main-image-preview" class="mb-3">
                        @if(old('main_image_url'))
                            <img src="{{ old('main_image_url') }}" class="h-32 object-cover rounded border">
                        @endif
                    </div>

                    <button type="button" onclick="openMediaModal('main')"
                        class="bg-red-50 text-red-600 px-4 py-2 rounded-lg border border-red-200 hover:bg-red-100 transition flex items-center">
                        <i class="fas fa-image mr-2"></i>
                        Select Main Image
                    </button>
                    @error('main_image_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Gallery Images -->
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                     <div id="gallery-container" class="grid grid-cols-3 md:grid-cols-5 gap-4 mb-3">
                         @if(old('gallery_image_urls'))
                             @foreach(old('gallery_image_urls') as $index => $url)
                                 <div class="relative group border rounded-lg overflow-hidden h-24 cursor-move gallery-item" data-id="{{ old('gallery_image_ids')[$index] }}">
                                     <img src="{{ $url }}" class="w-full h-full object-cover">
                                     <input type="hidden" name="gallery_image_ids[]" value="{{ old('gallery_image_ids')[$index] }}">
                                     <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                         <i class="fas fa-arrows-alt text-white"></i>
                                     </div>
                                     <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition z-10">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                     </button>
                                 </div>
                             @endforeach
                         @endif
                     </div>
                     <button type="button" onclick="openMediaModal('gallery')"
                        class="bg-stone-50 text-stone-600 px-4 py-2 rounded-lg border border-stone-200 hover:bg-stone-100 transition flex items-center font-medium">
                        <i class="fas fa-plus mr-2 text-xs"></i>
                        Add Images
                    </button>
                </div>
            </div>

            <!-- Simple Product Fields -->
            <div id="simple-product-fields" class="bg-white rounded-xl shadow-sm p-6 border border-stone-100 {{ old('product_type', 'simple') === 'simple' ? '' : 'hidden' }}">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 pb-2 border-b">Pricing & Inventory</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-stone-700 mb-1">Price <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-500 font-bold">₹</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition font-bold text-stone-800">
                        </div>
                        @error('price') <p class="text-rose-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-stone-700 mb-1">Compare at Price</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-500">₹</span>
                            <input type="number" name="compare_price" id="compare_price" value="{{ old('compare_price') }}" step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-stone-700 mb-1">Cost Price</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-500">₹</span>
                            <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-stone-700 mb-1">SKU <span class="text-rose-500">*</span></label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        @error('sku') <p class="text-rose-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-stone-700 mb-1">Stock Quantity <span class="text-rose-500">*</span></label>
                        <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity') }}"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        @error('stock_quantity') <p class="text-rose-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Configurable Product Fields -->
            <div id="configurable-product-fields" class="bg-white rounded-xl shadow-sm p-6 border border-stone-100 {{ old('product_type') === 'configurable' ? '' : 'hidden' }}">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 pb-2 border-b">Product Variants</h3>

                <!-- Attribute Selector -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-stone-700 mb-2">Select Attributes to Generate Variants</label>
                    <div id="attributes-loading" class="text-stone-500 italic hidden"><i class="fas fa-spinner fa-spin mr-2"></i>Loading attributes...</div>
                    <div id="attributes-selector" class="space-y-4">
                        <!-- Loaded via JS -->
                    </div>

                    <div class="mt-4">
                         <button type="button" onclick="generateVariants()" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl transition duration-200 font-bold shadow-lg shadow-red-100">
                            <i class="fas fa-magic mr-2"></i>Generate Variants
                        </button>
                    </div>
                </div>

                <!-- Generated Variants Table -->
                <div id="variants-container" class="overflow-x-auto">
                     <!-- Variants Table -->
                     @if(old('variants'))
                        <table class="min-w-full divide-y divide-stone-200 border rounded-xl overflow-hidden">
                            <thead class="bg-stone-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Variant</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider w-32">SKU</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider w-24">Price</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider w-24">Stock</th>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider w-48">Images</th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold text-stone-500 uppercase tracking-wider w-16">Default</th>
                                    <th class="px-3 py-2 text-center text-xs font-semibold text-stone-500 uppercase tracking-wider w-16">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(old('variants') as $idx => $variant)
                                    <tr id="variant-row-{{ $idx }}">
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                            @php
                                                $name = 'Variant ' . ($idx + 1);
                                            @endphp
                                            {{ $name }}
                                            
                                            @if(isset($variant['attributes']))
                                                @foreach($variant['attributes'] as $i => $attr)
                                                    <input type="hidden" name="variants[{{ $idx }}][attributes][{{ $i }}][attribute_id]" value="{{ $attr['attribute_id'] ?? '' }}">
                                                    <input type="hidden" name="variants[{{ $idx }}][attributes][{{ $i }}][attribute_value_id]" value="{{ $attr['attribute_value_id'] ?? '' }}">
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text" name="variants[{{ $idx }}][sku]" value="{{ $variant['sku'] ?? '' }}" class="w-full px-2 py-1 border rounded text-sm {{ $errors->has('variants.'.$idx.'.sku') ? 'border-red-500' : '' }}">
                                            @error('variants.'.$idx.'.sku') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="variants[{{ $idx }}][price]" value="{{ $variant['price'] ?? '' }}" step="0.01" class="w-full px-2 py-1 border rounded text-sm {{ $errors->has('variants.'.$idx.'.price') ? 'border-red-500' : '' }}">
                                            @error('variants.'.$idx.'.price') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="variants[{{ $idx }}][stock_quantity]" value="{{ $variant['stock_quantity'] ?? '' }}" class="w-full px-2 py-1 border rounded text-sm {{ $errors->has('variants.'.$idx.'.stock_quantity') ? 'border-red-500' : '' }}">
                                            @error('variants.'.$idx.'.stock_quantity') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="px-3 py-2">
                                            <div id="variant-images-{{ $idx }}" class="flex gap-1 flex-wrap">
                                                @if(!empty($variant['main_image_id']))
                                                    <div class="text-xs text-green-600 font-bold">Main Img Set</div>
                                                @endif
                                            </div>
                                            <button type="button" onclick="openVariantMediaModal({{ $idx }})" class="text-xs text-blue-600 hover:text-blue-800 mt-1">Manage Images</button>
                                            <input type="hidden" name="variants[{{ $idx }}][main_image_id]" id="variant-main-input-{{ $idx }}" value="{{ $variant['main_image_id'] ?? '' }}">
                                            
                                            <div id="variant-gallery-inputs-{{ $idx }}">
                                                @if(isset($variant['gallery_image_ids']))
                                                    @foreach($variant['gallery_image_ids'] as $gid)
                                                        <input type="hidden" name="variants[{{ $idx }}][gallery_image_ids][]" value="{{ $gid }}">
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="radio" name="default_variant_index" value="{{ $idx }}" {{ (old('default_variant_index') == $idx) ? 'checked' : '' }}
                                                onchange="document.querySelectorAll('.is-default-input').forEach((el, i) => el.value = (i === {{ $idx }} ? '1' : '0'))">
                                            <input type="hidden" id="is-default-{{ $idx }}" name="variants[{{ $idx }}][is_default]" value="{{ (old('variants.'.$idx.'.is_default') ?? '0') }}" class="is-default-input">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" onclick="removeVariant({{ $idx }})" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                     @endif
                </div>
            </div>

            <!-- Dynamic Specifications -->
            <div id="specifications-wrapper" class="hidden bg-white rounded-xl shadow-sm p-6 border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 pb-2 border-b">Specifications</h3>
                <div id="specifications-container" class="space-y-6">
                    <!-- Loaded via JS -->
                    <p class="text-stone-500 italic">Select a category to view specifications.</p>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Publish Status -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 border-b pb-2">Publish</h3>
                <div class="space-y-4">
                     <div>
                        <label for="status" class="block text-sm font-medium text-stone-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border border-stone-300 rounded-lg outline-none focus:ring-2 focus:ring-red-500">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="rounded text-red-500 focus:ring-red-500 h-4 w-4">
                        <label for="is_featured" class="text-sm text-stone-700">Featured Product</label>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_new" id="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}
                            class="rounded text-red-500 focus:ring-red-500 h-4 w-4">
                        <label for="is_new" class="text-sm text-stone-700">New Arrival</label>
                    </div>

                     <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" {{ old('is_bestseller') ? 'checked' : '' }}
                            class="rounded text-red-500 focus:ring-red-500 h-4 w-4">
                        <label for="is_bestseller" class="text-sm text-stone-700">Bestseller</label>
                    </div>

                    <div class="pt-4 border-t border-stone-100">
                        <button type="submit" class="w-full btn-primary py-3 justify-center shadow-lg shadow-red-100 uppercase tracking-widest text-xs font-bold">
                            <i class="fas fa-save mr-2"></i>Create Product
                        </button>
                    </div>
                </div>
            </div>

            <!-- Organization -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 border-b pb-2">Organization</h3>

                <div class="space-y-4">
                     <div>
                        <label for="product_type" class="block text-sm font-medium text-stone-700 mb-1">Product Type</label>
                        <select name="product_type" id="product_type" onchange="toggleProductType()"
                            class="w-full px-4 py-2 border border-red-200 bg-red-50 rounded-lg outline-none focus:ring-2 focus:ring-red-500 font-bold text-red-700">
                            <option value="simple" {{ old('product_type') == 'simple' ? 'selected' : '' }}>Simple Product</option>
                            <option value="configurable" {{ old('product_type') == 'configurable' ? 'selected' : '' }}>Configurable Product</option>
                        </select>
                    </div>

                    <div>
                        <label for="main_category_id" class="block text-sm font-medium text-stone-700 mb-1">Main Category <span class="text-rose-500">*</span></label>
                        <select name="main_category_id" id="main_category_id" required onchange="handleCategoryChange(this.value)"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('main_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @if($category->children)
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}" {{ old('main_category_id') == $child->id ? 'selected' : '' }}> -- {{ $child->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                        @error('main_category_id') <p class="text-rose-500 text-xs mt-1 italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-stone-700 mb-1">Brand</label>
                        <select name="brand_id" id="brand_id" class="w-full px-4 py-2 border border-stone-300 rounded-lg outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tag_ids" class="block text-sm font-medium text-stone-700 mb-2">Tags</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-3 border border-stone-300 rounded-lg bg-stone-50/50">
                            @php
                                $selectedTags = old('tag_ids', []);
                            @endphp
                            @foreach($tags as $tag)
                                <label class="flex items-center group cursor-pointer">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" 
                                            {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-stone-300 text-red-500 focus:ring-red-500 transition cursor-pointer">
                                    </div>
                                    <span class="ml-3 text-sm text-stone-600 group-hover:text-stone-800 transition">{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-stone-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg outline-none focus:ring-2 focus:ring-red-500">
                        <p class="text-[10px] text-stone-400 mt-1 uppercase tracking-wider font-bold">Lower numbers will display first</p>
                    </div>
                </div>
            </div>

            <!-- Shipping -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-stone-100">
                <h3 class="text-lg font-semibold text-stone-800 mb-4 border-b pb-2">Shipping</h3>
                 <div class="space-y-4">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-stone-700 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight') }}" step="0.001"
                            class="w-full px-4 py-2 border border-stone-300 rounded-lg outline-none focus:ring-2 focus:ring-red-500">
                        <p class="text-xs text-stone-500 mt-1">Note: Enter weight in KG <br>(e.g., 1 KG = 1.000, 100 GM = 0.100)</p>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                             <label class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Length</label>
                             <input type="number" name="length" value="{{ old('length') }}" placeholder="cm" class="w-full px-2 py-2 border border-stone-200 rounded-lg focus:ring-1 focus:ring-red-500 outline-none">
                        </div>
                        <div>
                             <label class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Width</label>
                             <input type="number" name="width" value="{{ old('width') }}" placeholder="cm" class="w-full px-2 py-2 border border-stone-200 rounded-lg focus:ring-1 focus:ring-red-500 outline-none">
                        </div>
                        <div>
                             <label class="block text-[10px] text-stone-400 uppercase font-bold mb-1">Height</label>
                             <input type="number" name="height" value="{{ old('height') }}" placeholder="cm" class="w-full px-2 py-2 border border-stone-200 rounded-lg focus:ring-1 focus:ring-red-500 outline-none">
                        </div>
                    </div>

                     <div>
                        <label for="tax_class_id" class="block text-sm font-medium text-stone-700 mb-1">Tax Class</label>
                        <select name="tax_class_id" id="tax_class_id" class="w-full px-4 py-2 border border-stone-300 rounded-lg outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">None</option>
                            @foreach($taxClasses as $tax)
                                <option value="{{ $tax->id }}" {{ old('tax_class_id') == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ number_format($tax->total_rate, 2) }}%)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="cod_available" id="cod_available" value="1" {{ old('cod_available') ? 'checked' : '' }}
                            class="rounded text-red-500 focus:ring-red-500 h-4 w-4">
                        <label for="cod_available" class="text-sm text-stone-700">COD Available</label>
                    </div>
                 </div>
            </div>

        </div>
    </div>
</form>

<!-- Media Modal -->
<div id="media-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeMediaModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full border border-stone-100">
            <div class="bg-white px-6 pt-6 pb-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-stone-800" id="modal-title">Media Library</h3>
                        <p class="text-sm text-stone-500">Select or upload images for your product</p>
                    </div>
                    <button type="button" onclick="closeMediaModal()" class="text-stone-400 hover:text-stone-600 p-2 hover:bg-stone-50 rounded-full transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <div class="relative w-full md:w-1/2">
                        <input type="text" id="media-search" placeholder="Search by filename..." 
                            class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl outline-none focus:ring-2 focus:ring-red-500 transition-all">
                        <i class="fas fa-search absolute left-4 top-3.5 text-stone-400"></i>
                    </div>
                     <div class="flex items-center space-x-3">
                        <label class="cursor-pointer btn-primary shadow-lg shadow-red-100 flex items-center">
                            <i class="fas fa-upload mr-2"></i>
                            <span>Upload New</span>
                            <input type="file" id="media-upload" class="hidden" multiple>
                        </label>
                    </div>
                </div>

                <div id="media-grid" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-[50vh] overflow-y-auto p-4 bg-stone-50 rounded-2xl border border-stone-100">
                    <div class="col-span-full text-center py-20 text-stone-400">
                        <i class="fas fa-spinner fa-spin text-3xl mb-3 text-red-500"></i>
                        <p class="font-medium">Loading your media library...</p>
                    </div>
                </div>

                <div id="media-pagination" class="mt-6 flex justify-between items-center">
                    <!-- Pagination links via JS -->
                </div>
            </div>
            <div class="bg-stone-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-stone-100">
                <button type="button" id="media-select-btn" onclick="confirmMediaSelection()" class="btn-primary min-w-[120px] justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                    Confirm selection
                </button>
                <button type="button" onclick="closeMediaModal()" class="btn-secondary min-w-[120px] justify-center">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
    // Global State
    let availableAttributes = [];
    let currentMode = 'main'; // 'main', 'gallery', 'variant-main', 'variant-gallery'
    let currentVariantIndex = null;
    let selectedImages = [];
    let currentMediaData = null;

    // Old Input Data
    const oldSpecifications = @json(old('specifications', []));
    const oldAttributes = @json(old('attributes', []));
    const oldVariants = @json(old('variants', []));
    const oldCategory = "{{ old('main_category_id') }}";
    const oldProductType = "{{ old('product_type') }}";

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Restore product type if exists, otherwise toggle default
        if(oldProductType) {
            document.getElementById('product_type').value = oldProductType;
        }
        toggleProductType();

        // Restore category and its dependencies
        if (oldCategory) {
            handleCategoryChange(oldCategory);
        }
        
        // Restore variants if any
        if (oldVariants && Object.keys(oldVariants).length > 0) {
            renderVariantsFromOld(oldVariants);
        }

        // Set up media upload
        document.getElementById('media-upload').addEventListener('change', handleFileUpload);

        // Initialize CKEditor
        if (document.querySelector('#description')) {
            ClassicEditor
                .create(document.querySelector('#description'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Show validation errors with toastr
                @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif

        // Initialize Sortable
        const galleryContainer = document.getElementById('gallery-container');
        if (galleryContainer) {
            new Sortable(galleryContainer, {
                animation: 150,
                ghostClass: 'bg-stone-50',
                dragClass: 'opacity-50'
            });
        }
    });


    // =============== PRODUCT TYPE & CATEGORY FUNCTIONS ===============

    function toggleProductType() {
        const type = document.getElementById('product_type').value;
        const simpleFields = document.getElementById('simple-product-fields');
        const configFields = document.getElementById('configurable-product-fields');

        if (type === 'simple') {
            simpleFields.classList.remove('hidden');
            configFields.classList.add('hidden');
        } else {
            simpleFields.classList.add('hidden');
            configFields.classList.remove('hidden');
            // Trigger Load Attributes if category is selected
            const catId = document.getElementById('main_category_id').value;
            if(catId) fetchAttributes(catId);
        }
    }

    // Slug Generator
    document.getElementById('name').addEventListener('input', function() {
        let slug = this.value.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    });

    function handleCategoryChange(categoryId) {
        if(!categoryId) return;
        fetchSpecifications(categoryId);

        if(document.getElementById('product_type').value === 'configurable') {
            fetchAttributes(categoryId);
        }
    }

    // =============== SPECIFICATIONS FUNCTIONS ===============

    async function fetchSpecifications(categoryId) {
        if (!categoryId) return;

        const container = document.getElementById('specifications-container');
        const wrapper = document.getElementById('specifications-wrapper');

        container.innerHTML = '<p class="text-gray-500">Loading specifications...</p>';
        wrapper.classList.remove('hidden');

        try {
            const response = await axios.get(`{{ route('admin.products.category.specifications', ':id') }}`.replace(':id', categoryId));

            if(response.data.success) {
                renderSpecifications(response.data.data);
            } else {
                container.innerHTML = '<p class="text-red-500">Failed to load specifications.</p>';
            }
        } catch (error) {
            console.error('Spec fetch error:', error);
            container.innerHTML = '<p class="text-red-500">Error loading specifications.</p>';
        }
    }

    function renderSpecifications(groups) {
         const container = document.getElementById('specifications-container');
         container.innerHTML = '';

         if (!groups || groups.length === 0) {
             container.innerHTML = '<p class="text-gray-500">No specifications found for this category.</p>';
             return;
         }

         let html = '';
         let specIndex = 0;

         groups.forEach(group => {
             html += `<div class="mb-6">`;
             html += `<h4 class="font-medium text-gray-700 mb-3 bg-gray-50 p-2 rounded">${group.group_name}</h4>`;
             html += `<div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;

             group.specifications.forEach(spec => {
                 const fieldName = `specifications[${specIndex}]`;
                 const oldSpec = oldSpecifications[specIndex] || {};

                 html += `<div>`;
                 html += `<input type="hidden" name="${fieldName}[specification_id]" value="${spec.id}">`;
                 html += `<label class="block text-sm text-gray-600 mb-1">${spec.name} ${spec.is_required ? '<span class="text-red-500">*</span>' : ''}</label>`;

                 const inputType = (spec.input_type || '').toLowerCase().trim();
                 
                 if (['select', 'multiselect', 'multi-select', 'radio'].includes(inputType)) {
                     const isMulti = inputType === 'multiselect' || inputType === 'multi-select';
                     const selectedValues = [].concat(oldSpec.specification_value_id || []);
                     
                     if (isMulti) {
                         html += `<div class="space-y-2 max-h-40 overflow-y-auto p-3 border rounded-lg bg-gray-50/30">`;
                         
                         // Create hidden input to ensure array is sent even if empty? 
                         // Actually, handling array input requires square brackets in name.
                         // We used specification_value_id[] in the select version.
                         // Here we will use custom_value_ids[] to match the logic often used for many-to-many sync, 
                         // OR keep using specification_value_id[] if the backend expects IDs of values.
                         // The Edit view used custom_value_ids[] for multiselect. Let's check edit view logic again.
                         // Edit view logic: name="${fieldName}[custom_value_ids][]"
                         // Wait, in Edit view:
                         // if (isMulti) ... name="${fieldName}[custom_value_ids][]"
                         // But in Create view originally: name="${fieldName}[specification_value_id]${isMulti ? '[]' : ''}"
                         // I should stick to what the Controller likely expects or what Edit view does if they share logic.
                         // If the user wants "just like edit.blade.php", I should use the Logic from Edit view.
                         // Edit view uses: name="${fieldName}[custom_value_ids][]"
                         // Let's assume ProductController handles both or aligns with this.
                         // I will use `custom_value_ids[]` to match Edit view exactly.
                         
                         if(spec.values) {
                             spec.values.forEach(val => {
                                 let isChecked = selectedValues.includes(String(val.id)) ? 'checked' : '';
                                 html += `
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="checkbox" name="${fieldName}[custom_value_ids][]" value="${val.id}" ${isChecked}
                                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-red-500 transition cursor-pointer">
                                        <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition">${val.value}</span>
                                    </label>
                                 `;
                             });
                         }
                         html += `</div>`;
                     } else {
                         html += `<select name="${fieldName}[specification_value_id]" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-red-500">`;
                         html += `<option value="">Select ${spec.name}</option>`;
                         if(spec.values) {
                             spec.values.forEach(val => {
                                 const isSelected = selectedValues.includes(String(val.id));
                                 html += `<option value="${val.id}" ${isSelected ? 'selected' : ''}>${val.value}</option>`;
                             });
                         }
                         html += `</select>`;
                     }
                 } else if (inputType === 'textarea') {
                     html += `<textarea name="${fieldName}[custom_value]" rows="3" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-red-500">${oldSpec.custom_value || ''}</textarea>`;
                 } else if (inputType === 'checkbox') {
                     const checked = oldSpec.custom_value == '1' ? 'checked' : '';
                     html += `
                        <div class="flex items-center mt-2">
                            <input type="hidden" name="${fieldName}[custom_value]" value="0">
                            <input type="checkbox" name="${fieldName}[custom_value]" value="1" ${checked} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-2 text-sm text-gray-600">Yes</span>
                        </div>
                     `;
                 } else {
                     html += `<input type="text" name="${fieldName}[custom_value]" value="${oldSpec.custom_value || ''}" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-1 focus:ring-red-500">`;
                 }

                 html += `</div>`;
                 specIndex++;
             });

             html += `</div></div>`;
         });

         container.innerHTML = html;
    }

    // =============== ATTRIBUTES & VARIANTS FUNCTIONS ===============

    async function fetchAttributes(categoryId) {
        const loading = document.getElementById('attributes-loading');
        const selector = document.getElementById('attributes-selector');

        loading.classList.remove('hidden');
        selector.innerHTML = '';
        availableAttributes = [];

        try {
            const response = await axios.get(`{{ route('admin.products.category.attributes', ':id') }}`.replace(':id', categoryId));
            loading.classList.add('hidden');

            if(response.data.success && response.data.data.length > 0) {
                availableAttributes = response.data.data;
                renderAttributeSelector(response.data.data);
            } else {
                selector.innerHTML = '<p class="text-gray-500 text-sm">No variant attributes found for this category.</p>';
            }
        } catch (error) {
            console.error('Attr fetch error:', error);
            loading.classList.add('hidden');
            selector.innerHTML = '<p class="text-red-500 text-sm">Error loading attributes.</p>';
        }
    }

    function renderAttributeSelector(attributes) {
        const selector = document.getElementById('attributes-selector');
        let html = '';

        attributes.forEach((attr, idx) => {
            html += `
            <div class="border rounded-lg p-4 bg-gray-50">
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="attr-enable-${attr.id}" data-idx="${idx}" class="attr-enable w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="attr-enable-${attr.id}" class="ml-2 font-medium text-gray-700">${attr.name}</label>
                </div>
                <select multiple id="attr-values-${attr.id}" class="attr-values w-full h-32 p-2 border rounded text-sm focus:outline-none focus:ring-1 focus:ring-red-500" disabled>
                    ${attr.options.map(opt => `<option value="${opt.id}">${opt.value}</option>`).join('')}
                </select>
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple values.</p>
            </div>
            `;
        });
        selector.innerHTML = html;

        // Add event listeners for enable/disable
        document.querySelectorAll('.attr-enable').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const attrId = this.id.replace('attr-enable-', '');
                const select = document.getElementById(`attr-values-${attrId}`);
                select.disabled = !this.checked;
            });
        });
    }

    function generateVariants() {
        const selectedAttrs = [];

        // Collect selected attributes and values
        availableAttributes.forEach((attr, idx) => {
             const checkbox = document.getElementById(`attr-enable-${attr.id}`);
             if(checkbox && checkbox.checked) {
                 const select = document.getElementById(`attr-values-${attr.id}`);
                 const values = Array.from(select.selectedOptions).map(opt => ({
                     id: opt.value,
                     value: opt.text
                 }));

                 if(values.length > 0) {
                     selectedAttrs.push({
                         id: attr.id,
                         name: attr.name,
                         values: values
                     });
                 }
             }
        });

        if(selectedAttrs.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Attributes Selected',
                text: 'Please select at least one attribute and value.'
            });
            return;
        }

        // Cartesian Product to generate combinations
        const combinations = cartesianProduct(selectedAttrs.map(a => a.values));

        // Render Table
        renderVariantsTable(combinations, selectedAttrs);
    }

    function cartesianProduct(arrays) {
        return arrays.reduce((acc, curr) => {
            return acc.flatMap(x => curr.map(y => [...x, y]));
        }, [[]]);
    }

    function renderVariantsTable(combinations, selectedAttrs) {
        const container = document.getElementById('variants-container');
        if(combinations.length === 0) {
            container.innerHTML = '<p class="text-gray-500">No variants generated. Please check your attribute selections.</p>';
            return;
        }

        const baseSku = document.getElementById('sku')?.value || 
                       document.getElementById('product_code')?.value || 
                       'PRD-' + Math.floor(Math.random() * 10000);
        const basePrice = document.getElementById('price')?.value || '0';

        let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">SKU</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Price</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Stock</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-48">Images</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-16">Default</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-16">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;

        combinations.forEach((combo, idx) => {
            const variantName = combo.map(c => c.value).join(' / ');
            const variantSku = `${baseSku}-${combo.map(c => c.value.replace(/\s+/g, '-').substring(0,3).toUpperCase()).join('-')}`;

            html += `
            <tr id="variant-row-${idx}">
                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                    ${variantName}
                    ${combo.map((c, i) => `
                        <input type="hidden" name="variants[${idx}][attributes][${i}][attribute_id]" value="${selectedAttrs[i].id}">
                        <input type="hidden" name="variants[${idx}][attributes][${i}][attribute_value_id]" value="${c.id}">
                    `).join('')}
                </td>
                <td class="px-3 py-2">
                    <input type="text" name="variants[${idx}][sku]" value="${variantSku}" class="w-full px-2 py-1 border rounded text-sm" required>
                </td>
                <td class="px-3 py-2">
                    <input type="number" name="variants[${idx}][price]" value="${basePrice}" step="0.01" min="0" class="w-full px-2 py-1 border rounded text-sm" required>
                </td>
                <td class="px-3 py-2">
                    <input type="number" name="variants[${idx}][stock_quantity]" value="0" min="0" class="w-full px-2 py-1 border rounded text-sm" required>
                </td>
                <td class="px-3 py-2">
                    <div id="variant-images-${idx}" class="flex gap-1 flex-wrap">
                        <!-- Images Preview -->
                    </div>
                    <button type="button" onclick="openVariantMediaModal(${idx})" class="text-xs text-blue-600 hover:text-blue-800 mt-1">Manage Images</button>
                    <input type="hidden" name="variants[${idx}][main_image_id]" id="variant-main-input-${idx}">
                    <div id="variant-gallery-inputs-${idx}"></div>
                </td>
                <td class="px-3 py-2 text-center">
                    <input type="radio" name="default_variant_index" value="${idx}" ${idx===0 ? 'checked' : ''}
                        onchange="document.querySelectorAll('.is-default-input').forEach((el, i) => el.value = (i === ${idx} ? '1' : '0'))">
                    <input type="hidden" id="is-default-${idx}" name="variants[${idx}][is_default]" value="${idx===0 ? '1' : '0'}" class="is-default-input">
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" onclick="removeVariant(${idx})" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
            `;
        });

        html += `</tbody></table></div>`;
        container.innerHTML = html;
        
        // Show success message
        toastr.success(`Generated ${combinations.length} variant(s)`);
    }

    function renderVariantsFromOld(variants) {
        const container = document.getElementById('variants-container');
        if(!variants) return;

        // Convert object to array if needed
        const variantsList = Array.isArray(variants) ? variants : Object.values(variants);
        
        if (variantsList.length === 0) return;

        let html = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">SKU</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Price</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Stock</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase w-48">Images</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-16">Default</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase w-16">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;

        variantsList.forEach((variant, idx) => {
            // Restore variant name from hidden attribute fields if available, otherwise generic
            const variantName = 'Variant ' + (idx + 1);

            html += `
            <tr id="variant-row-${idx}">
                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                    ${variantName}
                    ${Object.keys(variant.attributes || {}).map(attrId => 
                        `<input type="hidden" name="variants[${idx}][attributes][${attrId}]" value="${variant.attributes[attrId]}">`
                    ).join('')}
                </td>
                <td class="px-3 py-2">
                    <input type="text" name="variants[${idx}][sku]" value="${variant.sku || ''}" class="w-full px-2 py-1 border rounded text-sm" required>
                </td>
                <td class="px-3 py-2">
                    <input type="number" name="variants[${idx}][price]" value="${variant.price || ''}" step="0.01" min="0" class="w-full px-2 py-1 border rounded text-sm" required>
                </td>
                <td class="px-3 py-2">
                    <input type="number" name="variants[${idx}][stock_quantity]" value="${variant.stock_quantity || 0}" min="0" class="w-full px-2 py-1 border rounded text-sm" required>
                </td>
                <td class="px-3 py-2">
                    <div id="variant-images-${idx}" class="flex gap-1 flex-wrap">
                        ${variant.main_image_id ? '<div class="text-xs text-green-600 font-bold">Main Img Set</div>' : ''}
                    </div>
                    <button type="button" onclick="openVariantMediaModal(${idx})" class="text-xs text-blue-600 hover:text-blue-800 mt-1">Manage Images</button>
                    <input type="hidden" name="variants[${idx}][main_image_id]" id="variant-main-input-${idx}" value="${variant.main_image_id || ''}">
                    <!-- Gallery images restore not fully implemented for visualization, but inputs are kept -->
                </td>
                <td class="px-3 py-2 text-center">
                    <input type="radio" name="default_variant_index" value="${idx}" ${variant.is_default == '1' ? 'checked' : ''}
                        onchange="document.querySelectorAll('.is-default-input').forEach((el, i) => el.value = (i === ${idx} ? '1' : '0'))">
                    <input type="hidden" id="is-default-${idx}" name="variants[${idx}][is_default]" value="${variant.is_default || '0'}" class="is-default-input">
                </td>
                <td class="px-3 py-2 text-center">
                    <button type="button" onclick="removeVariant(${idx})" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>
            `;
        });

        html += `</tbody></table></div>`;
        container.innerHTML = html;
    }

    function removeVariant(idx) {
        document.getElementById(`variant-row-${idx}`).remove();
    }

    // =============== MEDIA MANAGEMENT FUNCTIONS ===============

    function openMediaModal(mode = 'main') {
        currentMode = mode;
        selectedImages = [];

        // Set modal title based on mode
        let modalTitle = 'Select Media';
        if (mode === 'main') modalTitle = 'Select Main Image';
        else if (mode === 'gallery') modalTitle = 'Select Gallery Images';
        else if (mode === 'variant-main') modalTitle = 'Select Variant Main Image';
        else if (mode === 'variant-gallery') modalTitle = 'Select Variant Gallery Images';

        document.getElementById('modal-title').textContent = modalTitle;

        // Show modal
        document.getElementById('media-modal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Load media
        loadMedia(1);
    }

    function closeMediaModal() {
        document.getElementById('media-modal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        selectedImages = [];
        currentMode = 'main';
        currentVariantIndex = null;
    }

    async function loadMedia(page = 1, search = '') {
        const grid = document.getElementById('media-grid');
        const pagination = document.getElementById('media-pagination');

        grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">Loading media...</div>';

        try {
            const response = await axios.get(`{{ route('admin.media.data') }}`, {
                params: { page, search }
            });

            currentMediaData = response.data;
            renderMediaGrid(response.data.data);
            renderPagination(response.data);
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
            html += `
            <div class="relative border rounded-lg overflow-hidden cursor-pointer group ${isSelected ? 'ring-2 ring-red-500' : ''}"
                 onclick="toggleImageSelection(${item.id}, '${item.url}')" data-media='${JSON.stringify(item)}'>
                <img src="${item.thumb_url}" class="w-full h-32 object-cover">
                <div class="p-2 text-xs truncate">${item.file_name}</div>
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
                const url = new URL(link.url, window.location.origin);
                const page = url.searchParams.get('page') || link.label.replace('&laquo; Previous', '1').replace('Next &raquo;', data.last_page);
                
                html += `
                <button onclick="loadMedia(${page}, document.getElementById('media-search').value)"
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
        if (currentMode === 'main' || currentMode === 'variant-main') {
            // Single selection mode - replace selection
            selectedImages = [{ id, url }];
        } else {
            // Multiple selection mode - toggle
            const existingIndex = selectedImages.findIndex(img => img.id === id);
            if (existingIndex === -1) {
                selectedImages.push({ id, url });
            } else {
                selectedImages.splice(existingIndex, 1);
            }
        }

        // Re-render grid with updated selection
        if (currentMediaData && currentMediaData.data) {
            renderMediaGrid(currentMediaData.data);
        }
    }

    function confirmMediaSelection() {
        if (selectedImages.length === 0) {
            toastr.warning('Please select at least one image');
            return;
        }

        if (currentMode === 'main') {
            const { id, url } = selectedImages[0];
            document.getElementById('main_image_id').value = id;
            document.getElementById('main-image-preview').innerHTML = `<img src="${url}" class="h-32 object-cover rounded border">`;
        }
        else if (currentMode === 'gallery') {
            selectedImages.forEach(({ id, url }) => {
                addGalleryImage(id, url);
            });
        }
        else if (currentMode === 'variant-main') {
            const { id, url } = selectedImages[0];
            setVariantMainImage(currentVariantIndex, id, url);
        }
        else if (currentMode === 'variant-gallery') {
            selectedImages.forEach(({ id, url }) => {
                addVariantGalleryImage(currentVariantIndex, id, url);
            });
        }

        closeMediaModal();
        toastr.success('Image(s) selected successfully');
    }

    function addGalleryImage(id, url) {
        const container = document.getElementById('gallery-container');

        // Check duplicate
        const inputs = container.querySelectorAll('input[name="gallery_image_ids[]"]');
        for(let input of inputs) {
            if(input.value == id) {
                toastr.warning('Image already in gallery');
                return;
            }
        }

        const div = document.createElement('div');
        div.className = "relative group border rounded-lg overflow-hidden h-24 cursor-move gallery-item";
        div.setAttribute('data-id', id);
        div.innerHTML = `
            <img src="${url}" class="w-full h-full object-cover">
            <input type="hidden" name="gallery_image_ids[]" value="${id}">
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                <i class="fas fa-arrows-alt text-white"></i>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        `;
        container.appendChild(div);
    }

    function setVariantMainImage(idx, id, url) {
        const container = document.getElementById(`variant-images-${idx}`);
        const input = document.getElementById(`variant-main-input-${idx}`);

        // Remove existing main image thumb if any
        const existing = container.querySelector('.variant-main-thumb');
        if(existing) existing.remove();

        input.value = id;

        // Create and prepend main image thumb
        const thumb = document.createElement('div');
        thumb.className = 'relative w-10 h-10 variant-main-thumb border-2 border-red-500 rounded overflow-hidden';
        thumb.innerHTML = `<img src="${url}" class="w-full h-full object-cover">`;
        container.prepend(thumb);
    }

    function addVariantGalleryImage(idx, id, url) {
        const container = document.getElementById(`variant-images-${idx}`);
        const hiddenContainer = document.getElementById(`variant-gallery-inputs-${idx}`);

        // Check duplicate
        if(hiddenContainer.querySelector(`input[value="${id}"]`)) {
            toastr.warning('Image already added to variant');
            return;
        }

        // Add Input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `variants[${idx}][gallery_image_ids][]`;
        input.value = id;
        hiddenContainer.appendChild(input);

        // Add Thumb
        const thumb = document.createElement('div');
        thumb.className = 'relative w-10 h-10 border border-gray-200 rounded overflow-hidden';
        thumb.innerHTML = `<img src="${url}" class="w-full h-full object-cover">`;
        container.appendChild(thumb);
    }

    function openVariantMediaModal(idx) {
        currentVariantIndex = idx;

        Swal.fire({
            title: 'Manage Variant Images',
            html: `
                <div class="text-left">
                    <button onclick="setVariantMainImageModal(${idx})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Set Main Image
                    </button>
                    <button onclick="addVariantGalleryImageModal(${idx})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Add Gallery Images
                    </button>
                </div>
            `,
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            showConfirmButton: false
        });
    }

    function setVariantMainImageModal(idx) {
        Swal.close();
        openMediaModal('variant-main');
    }

    function addVariantGalleryImageModal(idx) {
        Swal.close();
        openMediaModal('variant-gallery');
    }

    // =============== FILE UPLOAD FUNCTIONS ===============

    async function handleFileUpload(event) {
        const files = event.target.files;
        if (!files.length) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        try {
            const response = await axios.post('{{ route("admin.media.upload") }}', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            toastr.success('Files uploaded successfully');
            loadMedia(1); // Reload media grid
            event.target.value = ''; // Reset file input
        } catch (error) {
            console.error('Upload error:', error);
            toastr.error('Failed to upload files');
        }
    }

    // =============== UTILITY FUNCTIONS ===============
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Debounced search
    document.getElementById('media-search').addEventListener('input', debounce(function(e) {
        loadMedia(1, e.target.value);
    }, 500));

    // Handle Enter key in search
    document.getElementById('media-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            loadMedia(1, this.value);
        }
    });

    // Handle form submission
    document.getElementById('product-form').addEventListener('submit', function(e) {
        const productType = document.getElementById('product_type').value;

        if (productType === 'configurable') {
            const variantsExist = document.querySelectorAll('[id^="variant-row-"]').length > 0;
            if (!variantsExist) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Variants',
                    text: 'Please generate at least one variant for configurable product.'
                });
            }
        }
    });
</script>
@endpush
