<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function settings()
    {
        $settings = Setting::where('group', 'contact_info')->pluck('value', 'key')->toArray();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'studio_name' => $settings['studio_name'] ?? 'KNOTELLE Studio',
                'studio_badge' => $settings['studio_badge'] ?? 'Atelier Studio',
                'address_line_1' => $settings['address_line_1'] ?? '12th Main, 4th Cross, Indiranagar',
                'address_line_2' => $settings['address_line_2'] ?? 'Bengaluru, Karnataka 560038, India',
                'phone' => $settings['phone'] ?? '+91 98765 43210',
                'hours' => $settings['hours'] ?? 'Monday – Saturday: 10:00 AM – 7:00 PM IST',
                'email' => $settings['email'] ?? 'hello@knotelle.com',
                'response_time' => $settings['response_time'] ?? 'We reply within 24 hours',
            ]
        ]);
    }

    public function submit(Request $request)
    {
        $formSettings = \App\Models\Media::where('page', 'contact')
            ->where('section', 'contact_form')
            ->where('slot', 'form_settings')
            ->first();

        $meta = $formSettings && $formSettings->metadata ? $formSettings->metadata : [];
        $successMsg = $meta['success_message'] ?? 'Thank you! Your message has been sent successfully.';

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $meta['error_message'] ?? 'Please correct the errors and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['status'] = 'unread';

        $msg = ContactMessage::create($data);

        return response()->json([
            'status' => 'success',
            'message' => $successMsg,
            'data' => $msg
        ], 201);
    }
}
