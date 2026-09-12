<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('customer.pages.about');
    }

    public function contact()
    {
        return view('customer.pages.contact');
    }

    public function terms()
    {
        return view('customer.pages.terms');
    }

    public function shippingPolicy()
    {
        return view('customer.pages.shipping-policy');
    }

    public function refundPolicy()
    {
        return view('customer.pages.refund-policy');
    }

    public function privacyPolicy()
    {
        return view('customer.pages.privacy-policy');
    }

    public function faq()
    {
        return view('customer.pages.faq');
    }

    public function sizeGuide()
    {
        return view('customer.pages.size-guide');
    }

    public function concerns()
    {
        return view('customer.pages.concerns');
    }

    public function ingredients()
    {
        return view('customer.pages.ingredients');
    }
}
