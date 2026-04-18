<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Create Layup</p>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">{{ $supplier->name }}</h1>
        </div>
    </x-slot>

    <div class="shell">
        <div class="panel max-w-2xl p-6 sm:p-8">
            @include('partials.flash')

            <form method="POST" action="{{ route('suppliers.layups.store', $supplier) }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="label">Layup name</label>
                    <input id="name" name="name" value="{{ old('name') }}" class="field" placeholder="Panel Core A" required>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">Create Layup</button>
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
