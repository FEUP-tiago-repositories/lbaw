<div id="recoverModal" 
     class="hidden fixed backdrop-blur-sm inset-0 flex items-center justify-center z-50">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">

        <h2 class="text-2xl font-bold text-gray-800">Recover Password</h2>
        <p class="text-gray-600 mt-2 text-xl">
            Enter your email to receive a password recovery link.
        </p>

            @csrf
            <input type="email" name="email" placeholder="Your email"
                   required
                   class="w-full border-gray-300 rounded-xl p-3 shadow-sm">
            <div class="flex justify-center gap-4">

                <button type="button" onclick="closeRecoverModal()"
                        class="px-6 py-3 bg-red-600 text-lg text-white rounded-lg hover:bg-red-200 hover:text-black transition shadow text-center font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-3 text-lg bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium">
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
