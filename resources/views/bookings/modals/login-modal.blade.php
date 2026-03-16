<div id="loginModal" 
     class="hidden fixed backdrop-blur-sm inset-0 flex items-center justify-center z-[9999]">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center">

        <h2 class="text-2xl font-bold text-gray-800">Login Required</h2>
        
        <p class="text-gray-600 mt-2 text-xl">
            You need to login to continue. Go to login page?
        </p>

        <div class="mt-6 flex justify-center gap-4">
            <button onclick="closeLoginModal()"
                class="px-6 py-3 bg-red-500 text-lg text-white rounded-lg hover:bg-red-300 hover:text-black transition shadow text-center font-medium">
                No
            </button>

            <a href="{{ route('login') }}"
                class="px-6 py-3 bg-emerald-900 text-lg text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium inline-block decoration-0">
                Yes
            </a>
        </div>

    </div>
</div>

<script>
    function openLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
    }
</script>