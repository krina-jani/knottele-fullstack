@extends('admin.layouts.master')

@section('title', 'Testimonials')

@section('content')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-stone-800 mb-2">Testimonials</h2>
                <p class="text-stone-500 font-medium">Manage client success stories and feedback</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2 text-xs"></i>Add New Testimonial
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-2xl flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-stone-100 bg-stone-50/50">
            <h3 class="text-xl font-bold text-stone-800">All Testimonials</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-stone-50 text-stone-500 uppercase text-[10px] tracking-widest font-bold">
                        <th class="py-4 px-8 text-left">Client</th>
                        <th class="py-4 px-8 text-left">Designation</th>
                        <th class="py-4 px-8 text-center">Rating</th>
                        <th class="py-4 px-8 text-center">Status</th>
                        <th class="py-4 px-8 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-stone-600 text-sm font-medium">
                    @foreach($testimonials as $testimonial)
                    <tr class="border-b border-stone-50 hover:bg-red-50/30 transition-colors">
                        <td class="py-4 px-8 text-left">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 font-bold">
                                    {{ substr($testimonial->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-stone-800">{{ $testimonial->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-8 text-left">
                            <span class="text-stone-500 italic">{{ $testimonial->designation ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-8 text-center">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                <i class="fas fa-star mr-1 text-[10px]"></i> {{ $testimonial->rating }}
                            </div>
                        </td>
                        <td class="py-4 px-8 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $testimonial->is_active ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                <i class="fas {{ $testimonial->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1.5 text-[10px]"></i>
                                {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-4 px-8 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" 
                                   class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all hover:scale-110 shadow-sm"
                                   title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-100 transition-all hover:scale-110 shadow-sm"
                                            title="Delete">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($testimonials->hasPages())
        <div class="px-8 py-6 border-t border-stone-100 bg-stone-50/30">
            {{ $testimonials->links() }}
        </div>
        @endif
    </div>
@endsection
