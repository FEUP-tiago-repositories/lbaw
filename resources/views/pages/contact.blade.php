@extends('layouts.app')

@section('title', 'Contact Us - Sports Hub')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-emerald-800 mb-3">Get in Touch</h1>
            <p class="text-xl text-gray-600">
                We'd love to hear from you! Whether you have questions, feedback, or need assistance.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-emerald-800 mb-6">Contact Information</h2>

                <div class="space-y-6">
                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                            <a href="mailto:support@sportshub.pt" class="text-emerald-600 hover:text-emerald-800">
                                support@sportshub.pt
                            </a>
                            <p class="text-sm text-gray-500 mt-1">We'll respond within 24 hours</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Phone</h3>
                            <a href="tel:+351225081400" class="text-emerald-600 hover:text-emerald-800">
                                +351 225 081 400
                            </a>
                            <p class="text-sm text-gray-500 mt-1">Mon-Fri, 9:00 AM - 6:00 PM (WET)</p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Office Address</h3>
                            <p class="text-gray-600">
                                Rua Dr. Roberto Frias<br>
                                4200-465 Porto, Portugal<br>
                                Faculty of Engineering - University of Porto
                            </p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Follow Us</h3>
                            <div class="flex space-x-3">
                                <a href="#" class="text-gray-600 hover:text-emerald-700 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-emerald-700 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-emerald-700 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-emerald-700 transition">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-emerald-800 mb-6">Quick Links</h2>

                    <div class="space-y-4">
                        <a href="{{ route('faq') }}"
                           class="flex items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition group">
                            <svg class="w-8 h-8 text-emerald-600 mr-4 group-hover:scale-110 transition-transform"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-800">FAQ</h3>
                                <p class="text-sm text-gray-600">Find answers to common questions</p>
                            </div>
                        </a>

                        <a href="{{ route('about') }}"
                           class="flex items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition group">
                            <svg class="w-8 h-8 text-emerald-600 mr-4 group-hover:scale-110 transition-transform"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-800">About Us</h3>
                                <p class="text-sm text-gray-600">Learn about our mission and team</p>
                            </div>
                        </a>

                        <a href="{{ route('services') }}"
                           class="flex items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition group">
                            <svg class="w-8 h-8 text-emerald-600 mr-4 group-hover:scale-110 transition-transform"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-800">Our Services</h3>
                                <p class="text-sm text-gray-600">Discover what we offer</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Support Hours -->
                <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-lg shadow-lg p-8 text-white">
                    <h2 class="text-2xl font-bold mb-4">Support Hours</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Monday - Friday:</span>
                            <span class="font-semibold">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Saturday:</span>
                            <span class="font-semibold">10:00 AM - 2:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sunday:</span>
                            <span class="font-semibold">Closed</span>
                        </div>
                        <p class="text-sm opacity-90 mt-4 pt-4 border-t border-emerald-400">
                            All times are in Western European Time (WET)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Founders Contact -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-emerald-800 mb-6">Meet the Team</h2>
            <p class="text-gray-600 mb-6">
                Want to connect directly with our founders? Here's how to reach them:
            </p>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-3 text-emerald-700 text-xl font-bold">
                        GL
                    </div>
                    <h3 class="font-semibold text-gray-800">Gustavo Lourenço</h3>
                    <p class="text-sm text-gray-600 mb-2">Backend Lead</p>
                    <a href="mailto:up202306578@up.pt" class="text-xs text-emerald-600 hover:text-emerald-800">
                        up202306578@up.pt
                    </a>
                </div>

                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-3 text-emerald-700 text-xl font-bold">
                        TO
                    </div>
                    <h3 class="font-semibold text-gray-800">Tiago Oliveira</h3>
                    <p class="text-sm text-gray-600 mb-2">Frontend Lead</p>
                    <a href="mailto:up202007448@up.pt" class="text-xs text-emerald-600 hover:text-emerald-800">
                        up202007448@up.pt
                    </a>
                </div>

                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-3 text-emerald-700 text-xl font-bold">
                        FG
                    </div>
                    <h3 class="font-semibold text-gray-800">Francisco Gomes</h3>
                    <p class="text-sm text-gray-600 mb-2">Auth Systems</p>
                    <a href="mailto:up202306498@up.pt" class="text-xs text-emerald-600 hover:text-emerald-800">
                        up202306498@up.pt
                    </a>
                </div>

                <div class="border border-gray-200 rounded-lg p-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-3 text-emerald-700 text-xl font-bold">
                        TY
                    </div>
                    <h3 class="font-semibold text-gray-800">Tiago Yin</h3>
                    <p class="text-sm text-gray-600 mb-2">Search & UX Features</p>
                    <a href="mailto:up202306438@up.pt" class="text-xs text-emerald-600 hover:text-emerald-800">
                        up202306438@up.pt
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
