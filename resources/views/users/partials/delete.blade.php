<div id="deleteModal" class="hidden fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="relative p-5 border max-w-md bg-white rounded-2xl shadow-xl w-96 text-center text-xl">

        <h2 class="text-2xl font-bold text-gray-800">Are you sure?</h2>
        <p class="text-gray-600 mt-2 text-lg">
            Do you really want to delete your account? This action cannot be undone.
        </p>

        <div class="mt-6 flex justify-center gap-4">
            <!-- Cancel -->
            <button onclick="closeDeleteModal()"
                class="px-6 py-2 bg-emerald-900 text-lg text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium ">
                No
            </button>

            <!-- Confirm -->
            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button 
                    class="px-6 py-2 text-lg bg-red-500 text-white rounded-lg hover:bg-red-300 hover:text-black transition shadow text-center font-medium">
                    Yes, Delete
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
