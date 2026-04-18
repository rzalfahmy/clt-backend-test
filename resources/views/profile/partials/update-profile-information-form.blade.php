<section class="space-y-6">

    <header>
        <h2 class="text-xl font-bold text-white">
            Profile Information
        </h2>
        <p class="mt-1 text-sm text-stone-300">
            Update your name and email address.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label class="label">Name</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="field">
        </div>

        <div>
            <label class="label">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="field">
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button class="btn-primary">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-emerald-400 text-sm">Saved</span>
            @endif
        </div>

    </form>

</section>
