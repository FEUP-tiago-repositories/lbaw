@extends('layouts.app')

@section('title', 'Our Services - Sports Hub')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-900 rounded-3xl shadow-xl p-12 mb-10 text-white text-center">
            <h1 class="text-4xl font-bold mb-4">How SportsHub Works</h1>
            <p class="text-lg opacity-90 max-w-3xl mx-auto">
                Discover how we make booking sports activities simple, flexible, and hassle-free for everyone.
            </p>
        </div>

        <!-- Main Features Overview -->
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-emerald-800 mb-2">Search & Discover</h3>
                <p class="text-gray-600">Find the perfect sports space based on location, sport type, and availability.</p>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-emerald-800 mb-2">Book Instantly</h3>
                <p class="text-gray-600">Reserve your preferred time slot with secure, instant online booking.</p>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-emerald-800 mb-2">Play & Review</h3>
                <p class="text-gray-600">Enjoy your activity and share your experience with the community.</p>
            </div>
        </div>

        <!-- For Customers Section -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-8">
            <div class="flex items-center mb-6">
                <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h2 class="text-2xl font-bold text-emerald-800">For Sports Enthusiasts</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">No Long-Term Commitments</h3>
                        <p class="text-gray-600">Book activities on-demand without monthly memberships or contracts. Perfect for digital nomads, tourists, and flexible schedules.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Advanced Search & Filters</h3>
                        <p class="text-gray-600">Find exactly what you need with customizable filters for sport type, location, price range, ratings, and available time slots.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Detailed Facility Information</h3>
                        <p class="text-gray-600">View comprehensive details including photos, amenities, equipment quality, user reviews, and ratings before booking.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Instant Booking Confirmation</h3>
                        <p class="text-gray-600">Receive immediate confirmation via email and in-app notifications. Access all your bookings from your personalized dashboard.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Flexible Modifications</h3>
                        <p class="text-gray-600">Cancel or reschedule reservations before the start time. Manage everything easily from your profile.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Review & Rating System</h3>
                        <p class="text-gray-600">Share your experiences and read authentic reviews from verified users to make informed decisions.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Favorites & Recommendations</h3>
                        <p class="text-gray-600">Save your favorite spaces for quick access and receive personalized recommendations based on your preferences.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Secure Payments</h3>
                        <p class="text-gray-600">Multiple payment options with bank-level encryption. Your financial information is always protected.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Business Owners Section -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-8">
            <div class="flex items-center mb-6">
                <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h2 class="text-2xl font-bold text-emerald-800">For Facility Owners</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Easy Space Management</h3>
                        <p class="text-gray-600">Create, edit, and manage multiple sports spaces from one intuitive dashboard. Update photos, descriptions, and details anytime.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Flexible Scheduling</h3>
                        <p class="text-gray-600">Set custom availability, capacity limits, and pricing for different time slots. Optimize your schedule to maximize bookings.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Reservation Management</h3>
                        <p class="text-gray-600">Accept, decline, modify, or cancel bookings. View all reservations in an organized calendar format for easy planning.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Instant Notifications</h3>
                        <p class="text-gray-600">Get notified immediately when new bookings are made, so you can respond quickly and keep your calendar updated.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Dynamic Pricing & Discounts</h3>
                        <p class="text-gray-600">Create promotional offers and discounts to attract more customers during off-peak hours and increase overall revenue.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Customer Engagement</h3>
                        <p class="text-gray-600">Respond to reviews, build relationships with customers, and improve your reputation through transparent communication.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Increased Visibility</h3>
                        <p class="text-gray-600">Reach a wider audience of sports enthusiasts who might never have discovered your facility through traditional marketing.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Performance Analytics</h3>
                        <p class="text-gray-600">Track bookings, revenue trends, and customer feedback to make data-driven decisions for your business.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Features -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-8">
            <div class="flex items-center mb-6">
                <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                <h2 class="text-2xl font-bold text-emerald-800">Platform Features</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg p-6 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Responsive Design</h3>
                    <p class="text-gray-600">Access SportsHub seamlessly on desktop, tablet, or mobile. Our adaptive design ensures optimal usability across all devices.</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Interactive Maps</h3>
                    <p class="text-gray-600">Visualize sports facilities on an interactive map powered by OpenStreetMap. Find spaces near you with ease.</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600">99% uptime guarantee with encrypted data protection. Your information and payments are always secure.</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Smart Notifications</h3>
                    <p class="text-gray-600">Stay updated with real-time notifications for bookings, confirmations, cancellations, and upcoming reservations.</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">Multiple Payment Options</h3>
                    <p class="text-gray-600">Pay your way with credit cards, debit cards, or digital payment methods through trusted providers.</p>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 hover:border-emerald-300 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-xl text-gray-800 mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Our support team is here to help whenever you need assistance with bookings, payments, or general inquiries.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-3xl shadow-xl p-10 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-lg mb-8 opacity-90 max-w-2xl mx-auto">
                Join thousands of sports enthusiasts and facility owners who are already enjoying the flexibility and convenience of SportsHub.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('spaces.index') }}"
                   class="inline-block bg-white text-emerald-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Browse Sports Spaces
                </a>
                <a href="{{ route('register') }}"
                   class="inline-block bg-emerald-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-emerald-400 transition border-2 border-white">
                    Create an Account
                </a>
            </div>
        </div>
    </div>
@endsection
