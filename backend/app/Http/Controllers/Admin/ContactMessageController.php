<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

use App\Models\Setting;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        
        $settings = Setting::where('group', 'contact_info')->pluck('value', 'key')->toArray();
        
        // Default values if not set
        $contactSettings = [
            'studio_name' => $settings['studio_name'] ?? 'KNOTELLE Studio',
            'studio_badge' => $settings['studio_badge'] ?? 'Atelier Studio',
            'address_line_1' => $settings['address_line_1'] ?? '12th Main, 4th Cross, Indiranagar',
            'address_line_2' => $settings['address_line_2'] ?? 'Bengaluru, Karnataka 560038, India',
            'phone' => $settings['phone'] ?? '+91 98765 43210',
            'hours' => $settings['hours'] ?? 'Monday – Saturday: 10:00 AM – 7:00 PM IST',
            'email' => $settings['email'] ?? 'hello@knotelle.com',
            'response_time' => $settings['response_time'] ?? 'We reply within 24 hours',
        ];

        return view('admin.contact_messages.index', compact('messages', 'contactSettings'));
    }
    
    public function updateSettings(Request $request)
    {
        $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'hours' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'response_time' => 'required|string|max:255',
            'studio_name' => 'nullable|string|max:255',
            'studio_badge' => 'nullable|string|max:255',
        ]);
        
        $keys = ['address_line_1', 'address_line_2', 'phone', 'hours', 'email', 'response_time', 'studio_name', 'studio_badge'];
        
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['group' => 'contact_info', 'key' => $key],
                    [
                        'value' => $request->$key ?? '', 
                        'type' => 'string',
                        'label' => ucwords(str_replace('_', ' ', $key))
                    ]
                );
            }
        }
        
        return redirect()->route('admin.contact-messages.index')->with('success', 'Contact settings updated successfully.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();
        
        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted successfully.');
    }
}
