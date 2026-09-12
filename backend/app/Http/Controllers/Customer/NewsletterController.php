<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email'
        ], [
            'email.unique' => 'You are already subscribed to our newsletter!'
        ]);

        try {
            NewsletterSubscriber::create([
                'email' => $request->email,
                'is_active' => true
            ]);

            return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
