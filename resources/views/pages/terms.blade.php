@extends('layouts.app')

@section('title', 'Terms of Service - Sports Hub')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-emerald-800 mb-6">Terms of Service</h1>
            <p class="text-gray-600 mb-8">Last updated: December 21, 2025</p>

            <div class="space-y-6 text-gray-700">
                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">1. Acceptance of Terms</h2>
                    <p class="mb-4">
                        Welcome to SportsHub. By accessing or using our platform, you agree to be bound by these Terms of Service.
                        If you do not agree to these terms, please do not use our services.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">2. Description of Service</h2>
                    <p class="mb-4">
                        SportsHub is a web-based platform that connects users seeking flexible sports activities with sports
                        facilities and service providers. Our platform allows users to search, book, and experience different
                        sports activities without long-term commitments.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">3. User Accounts</h2>
                    <p class="mb-4">
                        To access certain features of SportsHub, you must create an account. You are responsible for:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Providing accurate and complete information during registration</li>
                        <li>Maintaining the confidentiality of your account credentials</li>
                        <li>All activities that occur under your account</li>
                        <li>Notifying us immediately of any unauthorized use</li>
                    </ul>
                    <p class="mt-4">
                        Users must be at least 18 years old to create an account. Each email address and phone number can
                        only be associated with one account.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">4. Bookings and Reservations</h2>
                    <p class="mb-4">
                        When making a reservation through SportsHub:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>You agree to pay the specified fees for the booked service</li>
                        <li>Reservations cannot be made more than 1 year in advance</li>
                        <li>You cannot double-book at the same time slot</li>
                        <li>Cancellations or modifications must be made before the reservation start time</li>
                        <li>Facilities may accept, decline, modify, or cancel your reservations</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">5. Reviews and User Content</h2>
                    <p class="mb-4">
                        Users may post reviews and comments about sports facilities. By posting content, you agree that:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>You can only review facilities you have actually visited</li>
                        <li>Your content must be honest, accurate, and respectful</li>
                        <li>You grant SportsHub a license to use, display, and distribute your content</li>
                        <li>Reviews remain on the platform even if you delete your account, but become anonymous</li>
                        <li>We reserve the right to remove inappropriate or fraudulent content</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">6. Business Owners</h2>
                    <p class="mb-4">
                        Business owners using SportsHub to list their facilities agree to:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Provide accurate information about their sports spaces</li>
                        <li>Honor confirmed reservations</li>
                        <li>Maintain their listed facilities in good condition</li>
                        <li>Respond to customer inquiries in a timely manner</li>
                        <li>Cannot offer discounts exceeding 100%</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">7. Payment Terms</h2>
                    <p class="mb-4">
                        All payments are processed through our secure third-party payment providers. SportsHub does not store
                        your complete payment information. Prices are displayed in local currency and include applicable taxes
                        unless otherwise stated.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">8. Privacy and Data Protection</h2>
                    <p class="mb-4">
                        Your privacy is important to us. We collect and process personal data in accordance with applicable
                        data protection laws. All sensitive information, including location and payment details, is encrypted.
                        For more details, please refer to our Privacy Policy.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">9. Prohibited Activities</h2>
                    <p class="mb-4">
                        Users are prohibited from:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Using the platform for any illegal purposes</li>
                        <li>Attempting to gain unauthorized access to other accounts</li>
                        <li>Posting false or misleading information</li>
                        <li>Harassing or threatening other users</li>
                        <li>Attempting to bypass security measures</li>
                        <li>Using automated systems to access the platform</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">10. Limitation of Liability</h2>
                    <p class="mb-4">
                        SportsHub acts as an intermediary platform connecting users with sports facilities. We are not
                        responsible for the quality, safety, or accuracy of the services provided by third-party facilities.
                        Users participate in sports activities at their own risk.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">11. System Availability</h2>
                    <p class="mb-4">
                        While we strive to maintain 99% availability in each 7-day cycle, we cannot guarantee uninterrupted
                        access to our services. We reserve the right to modify or discontinue services with or without notice.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">12. Account Termination</h2>
                    <p class="mb-4">
                        We reserve the right to suspend or terminate accounts that violate these Terms of Service. Upon
                        termination, your reviews will remain but become anonymous, and you will lose access to your account
                        and reservation history.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">13. Changes to Terms</h2>
                    <p class="mb-4">
                        We may update these Terms of Service from time to time. Continued use of the platform after changes
                        constitutes acceptance of the new terms. We will notify users of significant changes via email or
                        platform notifications.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-emerald-700 mb-3">14. Contact Information</h2>
                    <p class="mb-4">
                        If you have questions about these Terms of Service, please contact us through our
                        <a href="{{ route('contact') }}" class="text-emerald-600 hover:text-emerald-800 underline">Contact Page</a>.
                    </p>
                </section>
            </div>
        </div>
    </div>
@endsection
