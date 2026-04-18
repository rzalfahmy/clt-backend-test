<section class="space-y-6">

    <header>
        <h2 class="text-xl font-bold text-red-400">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-stone-300">
            This action cannot be undone. All your data will be deleted permanently.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')

        <button class="btn-danger">
            Delete Account
        </button>
    </form>

</section>
