<div id="recoverModal" 
     class="fixed backdrop-blur-sm inset-0 flex items-center justify-center z-50
            {{ $errors->recover->any() || session('status') ? '' : 'hidden' }}">
    <div class="bg-white border p-6 rounded-2xl shadow-xl w-96 text-center ">

        <h2 class="text-2xl font-bold text-gray-800">Recover Password</h2>
        <p class="text-gray-600 my-2">
            Enter your email to receive a password recovery link.
        </p>

        <form method="POST" action="/sign-in/recover">
            @csrf

            @if ($errors->has('email'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm">
                    {{ $errors->first('email') }}
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif
            <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Your email:</label>
            <input type="email" name="email" placeholder="Your email" id="email"
                   value="{{ old('email') }}"
                   required
                   class="w-full border-gray-300 rounded-xl p-3 shadow-sm mt-4">
            <div class="flex justify-center gap-4 mt-4">
                <button type="button" onclick="closeRecoverModal()"
                        class="px-6 py-2 bg-red-600 text-lg text-white rounded-lg hover:bg-red-200 hover:text-black transition shadow text-center font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium">
                    Send Link
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openRecoverModal() {
        document.getElementById('recoverModal').classList.remove('hidden');
    }

    function closeRecoverModal() {
        document.getElementById('recoverModal').classList.add('hidden');
    }
</script>
