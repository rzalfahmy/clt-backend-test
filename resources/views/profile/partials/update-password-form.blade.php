<section class="space-y-6">

    <header>
        <h2 class="text-xl font-bold text-white">
            Update Password
        </h2>
        <p class="mt-1 text-sm text-stone-300">
            Use a strong password for security.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label class="label">Current Password</label>
            <input type="password" name="current_password" class="field">
        </div>

        <div>
            <label class="label">New Password</label>
            <input type="password" name="password" class="field">
        </div>

        <div>
            <label class="label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="field">
        </div>

        <button class="btn-primary">
            Update Password
        </button>

    </form>

</section>
