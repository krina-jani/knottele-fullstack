@extends('admin.layouts.master')

@section('title', 'Contact Messages')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-stone-800 mb-2">Contact Messages</h2>
                <p class="text-stone-600 text-sm sm:text-base">View messages sent from the contact page</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-stone-200">
            <h3 class="text-lg font-semibold text-stone-800">Contact Page Information Settings</h3>
            <p class="text-sm text-stone-500 mb-4">Update the details shown on the frontend Contact page.</p>
            
            <form action="{{ route('admin.contact-messages.settings.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Address Section -->
                    <div class="p-4 bg-stone-50 rounded-xl border border-stone-200">
                        <div class="flex items-center gap-2 mb-3 text-red-600 font-semibold">
                            <i class="fas fa-map-marker-alt"></i> Visit Us
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-stone-700 mb-1">Address Line 1</label>
                            <input type="text" name="address_line_1" value="{{ old('address_line_1', $contactSettings['address_line_1']) }}" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Address Line 2 (City, Zip)</label>
                            <input type="text" name="address_line_2" value="{{ old('address_line_2', $contactSettings['address_line_2']) }}" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>

                    <!-- Phone Section -->
                    <div class="p-4 bg-stone-50 rounded-xl border border-stone-200">
                        <div class="flex items-center gap-2 mb-3 text-red-600 font-semibold">
                            <i class="fas fa-phone-alt"></i> Call Us
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-stone-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $contactSettings['phone']) }}" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Working Hours</label>
                            <input type="text" name="hours" value="{{ old('hours', $contactSettings['hours']) }}" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                    </div>

                    <!-- Email Section -->
                    <div class="p-4 bg-stone-50 rounded-xl border border-stone-200">
                        <div class="flex items-center gap-2 mb-3 text-red-600 font-semibold">
                            <i class="fas fa-envelope"></i> Email Us
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-stone-700 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $contactSettings['email']) }}" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">Response Time Text</label>
                            <input type="text" name="response_time" value="{{ old('response_time', $contactSettings['response_time']) }}" class="w-full px-3 py-2 border border-stone-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50 border-b border-stone-200">
                            <th class="p-4 font-semibold text-stone-800 text-sm">Date</th>
                            <th class="p-4 font-semibold text-stone-800 text-sm">Name</th>
                            <th class="p-4 font-semibold text-stone-800 text-sm">Contact Info</th>
                            <th class="p-4 font-semibold text-stone-800 text-sm">Message</th>
                            <th class="p-4 font-semibold text-stone-800 text-sm text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $msg)
                            <tr class="border-b border-stone-200 hover:bg-stone-50 transition-colors">
                                <td class="p-4 text-sm text-stone-600 whitespace-nowrap">
                                    {{ $msg->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="p-4 text-sm font-medium text-stone-800">
                                    {{ $msg->name }}
                                </td>
                                <td class="p-4 text-sm text-stone-600">
                                    <div><a href="mailto:{{ $msg->email }}" class="text-red-600 hover:underline">{{ $msg->email }}</a></div>
                                    @if($msg->phone)
                                        <div><a href="tel:{{ $msg->phone }}" class="text-red-600 hover:underline">{{ $msg->phone }}</a></div>
                                    @endif
                                </td>
                                <td class="p-4 text-sm text-stone-600">
                                    <div class="max-w-xs md:max-w-md lg:max-w-lg break-words whitespace-pre-wrap">
                                        {{ $msg->message }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors mx-auto" title="Delete Message">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-stone-500">
                                    No contact messages found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
