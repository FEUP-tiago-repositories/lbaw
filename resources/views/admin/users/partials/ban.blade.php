{{-- Ban User Modal --}}
<div id="banModal" 
     class="fixed backdrop-blur-sm inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">
        <h2 class="text-2xl font-bold text-gray-800">Ban User</h2>
        <p class="text-gray-600 mt-2 text-xl">
            Provide a reason for banning this user.
        </p>
        
        <form id="banForm" method="POST" action="">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif
            
            @if (session('ban_success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                    {{ session('ban_success') }}
                </div>
            @endif
            
            <textarea name="motive" 
                      placeholder="Reason for ban"
                      required
                      rows="4"
                      class="w-full border-gray-300 rounded-xl p-3 shadow-sm mt-4">{{ old('motive') }}</textarea>
            
            <div class="flex justify-center gap-4 mt-4">
                <button type="button" onclick="closeBanModal()"
                        class="px-6 py-3 bg-red-600 text-lg text-white rounded-lg hover:bg-red-200 hover:text-black transition shadow text-center font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 text-lg bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium">
                    Ban User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBanModal(userId) {
        const form = document.getElementById('banForm');
        form.action = `/admin/users/${userId}/ban`;
        document.getElementById('banModal').classList.remove('hidden');
    }
    
    function closeBanModal() {
        document.getElementById('banModal').classList.add('hidden');
    }
</script>