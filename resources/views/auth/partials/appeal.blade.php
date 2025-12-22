<div id="appealModal" 
     class="fixed backdrop-blur-sm inset-0 flex items-center justify-center z-50
            {{ ($errors->appeal->any() || session('banned') || session('appeal_success')) ? '' : 'hidden' }}">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">
        <h2 class="text-2xl font-bold text-gray-800">Banned</h2>
        <p class="text-gray-600 mt-2 text-xl">
            You have been banned for the following reason:
        </p>
        <p class="text-gray-600 mt-2 text-xl font-semibold">
            {{ session('ban_motive') }}
        </p>

        <p class="text-gray-600 mt-2 text-xl">
            You can send an appeal below to request an unban:
        </p>
        
        <form id="appealForm" method="POST" action="{{ route('sendAppeal') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ session('user_id') }}">
            @if ($errors->appeal->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                    {{ $errors->appeal->first('appeal') }}
                </div>
            @endif
            
            @if (session('appeal_success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                    {{ session('appeal_success') }}
                </div>
            @endif
            
            <textarea name="appeal" 
                      placeholder="Appeal for ban"
                      required
                      rows="4"
                      class="w-full border-gray-300 rounded-xl p-3 shadow-sm mt-4">{{ old('appeal') }}</textarea>
            
            <div class="flex justify-center gap-4 mt-4">
                <button type="button" onclick="closeAppealModal()"
                        class="px-6 py-3 bg-red-600 text-lg text-white rounded-lg hover:bg-red-200 hover:text-black transition shadow text-center font-medium">
                    Exit
                </button>
                <button type="submit" 
                        class="px-6 py-3 text-lg bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium">
                    Appeal Ban
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    @if($errors->appeal->any() || session('banned') || session('appeal_success'))
        document.addEventListener('DOMContentLoaded', function() {
            openAppealModal();
        });
    @endif
function openAppealModal() {
    document.getElementById('appealModal').classList.remove('hidden');
}

function closeAppealModal() {
    document.getElementById('appealModal').classList.add('hidden');
}
</script>
