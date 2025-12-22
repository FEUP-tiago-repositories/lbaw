<footer class="sticky top-[100vh] bg-emerald-800 text-white mt-auto">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <!-- Left side - Copyright -->
            <div class="text-center md:text-left">
                <p class="opacity-90">© 2025 SportsHub. All rights reserved.</p>
            </div>

            <!-- Right side - Links -->
            <div class="flex flex-wrap justify-center md:justify-end gap-6 ">
                <a href="{{ route('about') }}" class="hover:text-emerald-200 transition">
                    About Us
                </a>
                <a href="{{ route('services') }}" class="hover:text-emerald-200 transition">
                    Services
                </a>
                <a href="{{ route('faq') }}" class="hover:text-emerald-200 transition">
                    FAQ
                </a>
                <a href="{{ route('contact') }}" class="hover:text-emerald-200 transition">
                    Contact
                </a>
                <a href="{{ route('terms') }}" class="hover:text-emerald-200 transition">
                    Terms of Service
                </a>
            </div>
        </div>
    </div>
</footer>