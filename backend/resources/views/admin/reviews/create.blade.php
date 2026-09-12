@extends('admin.layouts.master')

@section('title', 'Create Review')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center text-stone-400 hover:text-red-600 transition-colors mb-4 group font-bold text-sm uppercase tracking-widest">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
            Back to Reviews
        </a>
        <h2 class="text-3xl font-black text-stone-800 tracking-tight">Add New Review</h2>
        <p class="text-stone-500 font-medium mt-1">Manually curate product reviews and ratings</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="p-8 sm:p-12">
            <form action="{{ route('admin.reviews.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div>
                        <label class="block text-xs font-black text-stone-400 uppercase tracking-[0.2em] mb-3" for="product_id">
                            Target Product
                        </label>
                        <div class="relative">
                            <select name="product_id" id="product_id" class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 text-stone-700 font-bold focus:outline-none focus:ring-2 focus:ring-red-500 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>Select a product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-stone-300 pointer-events-none"></i>
                        </div>
                        @error('product_id')
                            <p class="text-rose-500 text-xs font-bold mt-2 ml-1 tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-stone-400 uppercase tracking-[0.2em] mb-3" for="user_name">
                            Reviewer Name
                        </label>
                        <input type="text" name="user_name" id="user_name" placeholder="e.g. Alexander Pierce"
                            class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 text-stone-800 font-bold focus:outline-none focus:ring-2 focus:ring-red-500 transition-all placeholder:text-stone-300" 
                            value="{{ old('user_name') }}">
                        @error('user_name')
                            <p class="text-rose-500 text-xs font-bold mt-2 ml-1 tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-10">
                    <label class="block text-xs font-black text-stone-400 uppercase tracking-[0.2em] mb-4">
                        Quality Rating
                    </label>
                    <div class="flex items-center space-x-6 bg-stone-50 p-6 rounded-3xl border border-stone-100">
                        <div class="flex-1">
                            <input type="range" name="rating" id="rating_range" min="1" max="5" step="0.5" value="{{ old('rating', 5) }}"
                                class="w-full h-2 bg-stone-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                            <div class="flex justify-between mt-3 text-[10px] font-black text-stone-300 uppercase tracking-widest">
                                <span>1.0 Star</span>
                                <span>3.0 Stars</span>
                                <span>5.0 Stars</span>
                            </div>
                        </div>
                        <div class="w-20 text-center">
                            <span id="rating_display" class="text-3xl font-black text-red-600 tracking-tighter">{{ old('rating', 5.0) }}</span>
                            <p class="text-[9px] font-black text-stone-400 uppercase">Score</p>
                        </div>
                    </div>
                    @error('rating')
                        <p class="text-rose-500 text-xs font-bold mt-2 ml-1 tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-10">
                    <label class="block text-xs font-black text-stone-400 uppercase tracking-[0.2em] mb-3" for="review">
                        Review Content
                    </label>
                    <textarea name="review" id="review" rows="6" placeholder="Write the customer's experience here..."
                        class="w-full bg-stone-50 border border-stone-200 rounded-2xl px-5 py-4 text-stone-800 font-bold focus:outline-none focus:ring-2 focus:ring-red-500 transition-all placeholder:text-stone-300 resize-none">{{ old('review') }}</textarea>
                    @error('review')
                        <p class="text-rose-500 text-xs font-bold mt-2 ml-1 tracking-tight">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pt-6 border-t border-stone-50">
                    <div class="flex items-center">
                        <input type="hidden" name="status" value="0">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500 group-hover:scale-105 transition-transform"></div>
                            <span class="ml-4 text-xs font-black text-stone-400 uppercase tracking-widest peer-checked:text-red-600 transition-colors">Visible to Public</span>
                        </label>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.reviews.index') }}" class="btn-secondary px-8">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary px-10">
                            Create Review
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const range = document.getElementById('rating_range');
        const display = document.getElementById('rating_display');
        
        range.addEventListener('input', function() {
            display.textContent = parseFloat(this.value).toFixed(1);
        });
    });
</script>
@endsection
