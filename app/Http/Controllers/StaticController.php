<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticController extends Controller
{
    /**
     * Display the about page.
     * US21 - See About page
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the FAQ page.
     * US23 - Consult FAQ page
     */
    public function faq()
    {
        return view('pages.faq');
    }

    /**
     * Display the terms of service page.
     * US20-2 - See Terms of Service
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Display the contact page.
     * US24 - Consult Contacts page
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Display the services page.
     * US22 - See Services information
     */
    public function services()
    {
        return view('pages.services');
    }
}
