@extends('layouts.app')

@section('title', 'About Us - Sports Hub')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-900 rounded-3xl shadow-xl p-12 mb-8 text-white">
            <h1 class="text-4xl font-bold mb-4">About SportsHub</h1>
            <p class="text-xl opacity-90">
                Connecting passionate athletes with flexible sports opportunities, anytime, anywhere.
            </p>
        </div>

        <!-- Our Story Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="flex items-center mb-6">
                <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h2 class="text-3xl font-bold text-emerald-800">Our Story</h2>
            </div>

            <div class="space-y-4 text-gray-700 leading-relaxed">
                <p>
                    SportsHub was born in 2025 from a simple observation: traditional sports facilities rely on rigid
                    membership models that don't fit modern lifestyles. Whether you're a digital nomad, a tourist, or
                    someone with an unpredictable schedule, finding and booking sports activities should be easy and flexible.
                </p>

                <p>
                    Four engineering students from the Faculty of Engineering, University of Porto (FEUP) –
                    <span class="font-semibold text-emerald-700">Gustavo Lourenço</span>,
                    <span class="font-semibold text-emerald-700">Tiago Oliveira</span>,
                    <span class="font-semibold text-emerald-700">Francisco Gomes</span>, and
                    <span class="font-semibold text-emerald-700">Tiago Yin</span> – decided to tackle this challenge.
                    United by their passion for sports and technology, they envisioned a platform that would revolutionize
                    how people access sports facilities.
                </p>

                <p>
                    What started as a university project quickly evolved into something much bigger. The team realized
                    they weren't just solving a problem for themselves – they were addressing a fundamental gap in the
                    sports and fitness industry. Facilities struggled with underutilized spaces during off-peak hours,
                    while potential customers couldn't find flexible options that matched their needs.
                </p>

                <p>
                    Today, SportsHub is a thriving platform that bridges this gap, creating a win-win situation where
                    users gain maximum flexibility and variety, while businesses achieve better profitability and exposure.
                    Our journey from a university classroom to serving countless sports enthusiasts is a testament to the
                    power of identifying real problems and building practical solutions.
                </p>
            </div>
        </div>

        <!-- Mission Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h2 class="text-3xl font-bold text-emerald-800">Our Mission</h2>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    To simplify access to sports activities by creating a unified marketplace that allows people to
                    easily discover, compare, and book sports experiences whenever and wherever they want. We're breaking
                    down barriers like monthly fees, lack of facility knowledge, and difficulty with last-minute bookings
                    to make sports truly accessible for everyone.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <h2 class="text-3xl font-bold text-emerald-800">Our Vision</h2>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    We envision a world where staying active is effortless. Where anyone can walk into any city and
                    instantly find and book sports facilities that match their interests. Where sports facility owners
                    maximize their space utilization and reach. A future where sports participation is no longer limited
                    by geography, time commitments, or lack of information.
                </p>
            </div>
        </div>

        <!-- Values Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="flex items-center mb-8">
                <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <h2 class="text-3xl font-bold text-emerald-800">Our Core Values</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-emerald-50 rounded-lg">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Flexibility</h3>
                    <p class="text-gray-600">
                        No long-term commitments. Book when you want, where you want.
                    </p>
                </div>

                <div class="text-center p-6 bg-emerald-50 rounded-lg">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Trust</h3>
                    <p class="text-gray-600">
                        Transparent reviews and secure payments build confidence.
                    </p>
                </div>

                <div class="text-center p-6 bg-emerald-50 rounded-lg">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Community</h3>
                    <p class="text-gray-600">
                        Connecting sports enthusiasts and facility owners together.
                    </p>
                </div>

                <div class="text-center p-6 bg-emerald-50 rounded-lg">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-600 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Innovation</h3>
                    <p class="text-gray-600">
                        Leveraging technology to reimagine sports accessibility.
                    </p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <!-- Team Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center mb-8">
                <svg class="w-10 h-10 mr-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h2 class="text-3xl font-bold text-emerald-800">Meet the Founders</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-600 rounded-full mb-4 text-white text-2xl font-bold">
                        GL
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800">Gustavo Lourenço</h3>
                    <p class="text-gray-600 text-sm">Co-Founder & Backend Lead</p>
                    <p class="text-gray-500 text-xs mt-2">FEUP - LEIC</p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-600 rounded-full mb-4 text-white text-2xl font-bold">
                        TO
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800">Tiago Oliveira</h3>
                    <p class="text-gray-600 text-sm">Co-Founder & Frontend Lead</p>
                    <p class="text-gray-500 text-xs mt-2">FEUP - LEIC</p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-600 rounded-full mb-4 text-white text-2xl font-bold">
                        FG
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800">Francisco Gomes</h3>
                    <p class="text-gray-600 text-sm">Co-Founder & Auth Systems</p>
                    <p class="text-gray-500 text-xs mt-2">FEUP - LEIC</p>
                </div>

                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-emerald-600 rounded-full mb-4 text-white text-2xl font-bold">
                        TY
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800">Tiago Yin</h3>
                    <p class="text-gray-600 text-sm">Co-Founder & Search & UX Features</p>
                    <p class="text-gray-500 text-xs mt-2">FEUP - LEIC</p>
                </div>
            </div>
        </div>


        <!-- CTA Section -->
        <div class="mt-8 bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl shadow-xl p-8 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Join Our Journey</h2>
            <p class="text-lg mb-6 opacity-90">
                Ready to experience flexible sports booking? Start exploring amazing facilities today.
            </p>
            <a href="{{ route('spaces.index') }}"
               class="inline-block bg-white text-emerald-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Explore Sports Spaces
            </a>
        </div>
    </div>
@endsection
