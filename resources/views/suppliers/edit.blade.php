<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Edit Supplier</p>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">{{ $supplier->name }}</h1>
        </div>
    </x-slot>

    <div class="shell">
        <div class="panel max-w-2xl p-6 sm:p-8">
            @include('partials.flash')

            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="label">Supplier name</label>
                    <input id="name" name="name" value="{{ old('name', $supplier->name) }}" class="field" required>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">Save Changes</button>
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
