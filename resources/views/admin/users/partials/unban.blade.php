{{-- Unban User Modal --}}
<div id="unbanModal" 
     class="fixed backdrop-blur-sm inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">
        <h2 class="text-2xl font-bold text-gray-800">Unban User</h2>
        <p class="text-gray-600 mt-2 text-xl">
            Ban Reason:
        </p>
        
        {{-- Show the motive --}}
        <div id="banMotive" class="bg-gray-100 text-gray-800 p-3 rounded-lg my-4 text-sm">

        </div>

        <form id="unbanForm" method="POST" action="">
            @csrf
            @method('POST')
            <div class="flex justify-center gap-4 mt-4">
                <button type="button" onclick="closeUnbanModal()"
                        class="px-6 py-3 bg-red-600 text-lg text-white rounded-lg hover:bg-red-200 hover:text-black transition shadow text-center font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 text-lg bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium">
                    Unban User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUnbanModal(userId,motive) {
        const form = document.getElementById('unbanForm');
        form.action = `/admin/users/${userId}/unban`;
        document.getElementById('banMotive').textContent = motive;
        document.getElementById('unbanModal').classList.remove('hidden');
    }
    
    function closeUnbanModal() {
        document.getElementById('unbanModal').classList.add('hidden');
    }
</script>