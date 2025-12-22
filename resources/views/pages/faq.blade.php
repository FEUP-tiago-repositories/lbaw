@extends('layouts.app')

@section('title', 'FAQ - Sports Hub')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-emerald-800 mb-3">Frequently Asked Questions</h1>
            <p class="text-xl text-gray-600">
                Find answers to common questions about using SportsHub
            </p>
        </div>

        <!-- Search Box -->
        <div class="mb-8">
            <div class="relative">
                <input type="text"
                       id="faq-search"
                       placeholder="Search for answers..."
                       class="w-full px-6 py-4 pr-12 border-2 border-emerald-300 rounded-lg focus:outline-none focus:border-emerald-500 text-lg">
                <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="space-y-8" id="faq-container">
            <!-- General Questions -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-emerald-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        General Questions
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>What is SportsHub?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            SportsHub is a web-based platform that connects people seeking flexible sports activities with sports facilities
                            and service providers. Unlike traditional membership models, we allow you to book sports activities on-demand,
                            anytime and anywhere, without long-term commitments.
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Do I need to create an account to use SportsHub?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            You can browse sports facilities and check availability without an account. However, to make bookings,
                            write reviews, or save favorite spaces, you'll need to create a free account. Registration only takes a minute!
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Is there a minimum age requirement?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Yes, you must be at least 18 years old to create an account on SportsHub. This ensures compliance with legal
                            requirements and helps us maintain a safe community.
                        </div>
                    </div>

                    <div class="faq-item pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Which cities does SportsHub operate in?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            We're continuously expanding! Currently, we operate primarily in Portugal, with strong presence in Porto. We're working to add more cities and facilities regularly.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking & Reservations -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-emerald-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Booking & Reservations
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>How do I make a reservation?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Simply search for sports spaces by location, sport type, or date. When you find a space you like, select your
                            preferred time slot, complete the payment, and you're all set! You'll receive a confirmation via email and
                            can view all your reservations in your profile.
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Can I book multiple spaces at the same time?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            No, you cannot double-book at the same time slot. This restriction prevents scheduling conflicts and ensures
                            you honor each booking. However, you can book different spaces for different time periods.
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>How far in advance can I book?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            You can make reservations up to 1 year in advance. This gives you plenty of flexibility to plan ahead for regular
                            activities or special events.
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Can I cancel or modify my reservation?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Yes! You can cancel or modify your reservations, but only before the reservation start time. Simply go to
                            "My Reservations" in your profile to make changes.
                        </div>
                    </div>

                    <div class="faq-item pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>What happens if a facility cancels my booking?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            If a facility needs to cancel your reservation, you'll receive an immediate notification and a full refund.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-emerald-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Payments
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>What payment methods do you accept?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            We accept major credit cards (Visa, MasterCard, American Express), debit cards, and various digital payment
                            methods through our secure payment providers (as Mb Way and PayPal). All transactions are encrypted and secure.
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Is my payment information secure?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Absolutely. We use industry-standard encryption to protect your payment details. SportsHub does not store your
                            complete payment information – all transactions are processed through certified third-party payment providers.
                        </div>
                    </div>

                    <div class="faq-item pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>When will I be charged for a reservation?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Payment is processed immediately when you confirm your booking. If the facility declines your reservation, you'll receive an automatic refund.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews & Ratings -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-emerald-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Reviews & Ratings
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Can I leave a review for any sports space?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            You can only review sports spaces where you've actually had a confirmed booking in the past. This ensures all
                            reviews are genuine and based on real experiences, helping other users make informed decisions.
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>What happens to my reviews if I delete my account?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Your reviews remain on the platform but become anonymous. This preserves valuable feedback for the community
                            while respecting your decision to leave. Other users can still benefit from your honest opinions.
                        </div>
                    </div>

                    <div class="faq-item pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Can facility owners respond to my review?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Yes, business owners can respond to reviews. This creates a transparent dialogue and allows facilities to
                            address concerns, thank customers for positive feedback, or provide context for specific situations.
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Business Owners -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-emerald-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        For Business Owners
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>How do I list my sports facility on SportsHub?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Create a Business Owner account, then use the "Create Space" feature to add your facilities. You'll need to
                            provide details like location, sports type, capacity, opening hours, and photos. Once submitted, you need create schedules and can start
                            receiving bookings immediately!
                        </div>
                    </div>

                    <div class="faq-item border-b border-gray-200 pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Can I manage my facility's availability and reservations?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Absolutely! You have full control over schedules, capacity and discounts. You can also accept, decline,
                            or modify customer reservations through your dashboard and view all bookings in a convenient calendar format.
                        </div>
                    </div>

                    <div class="faq-item pb-4">
                        <button class="faq-question w-full text-left flex justify-between items-center font-semibold text-lg text-gray-800 hover:text-emerald-700">
                            <span>Is there a fee for listing my facility?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer hidden mt-3 text-gray-600 leading-relaxed">
                            Creating a listing is free! We operate on a commission-based model, taking a small percentage only from confirmed
                            bookings. This means you only pay when you earn, with no upfront costs or monthly fees.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Still have questions -->
        <div class="mt-10 bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl shadow-xl p-8 text-white text-center">
            <h2 class="text-2xl font-bold mb-3">Still have questions?</h2>
            <p class="mb-6 opacity-90">We're here to help! Contact our support team for personalized assistance.</p>
            <a href="{{ route('contact') }}"
               class="inline-block bg-white text-emerald-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Contact Support
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            // FAQ Accordion
            document.querySelectorAll('.faq-question').forEach(button => {
                button.addEventListener('click', () => {
                    const answer = button.nextElementSibling;
                    const icon = button.querySelector('svg');

                    answer.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });

            // FAQ Search
            const searchInput = document.getElementById('faq-search');
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const faqItems = document.querySelectorAll('.faq-item');

                faqItems.forEach(item => {
                    const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                    const answer = item.querySelector('.faq-answer').textContent.toLowerCase();

                    if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush
@endsection
