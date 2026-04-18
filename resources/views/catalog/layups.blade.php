<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Catalog</p>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">Layups</h1>
        </div>
    </x-slot>

    <div class="shell space-y-8">
        <section class="panel p-6 sm:p-8">
            <form method="GET" action="{{ route('layups.index') }}" class="flex w-full max-w-xl flex-col gap-3 sm:flex-row">
                <input type="text" name="search" value="{{ $search }}" class="field mt-0" placeholder="Search layups by name...">
                <button type="submit" class="btn-secondary">Search</button>
            </form>

            <div class="mt-6 overflow-hidden rounded-3xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.16em] text-stone-400">
                        <tr>
                            <th class="px-5 py-4">Layup</th>
                            <th class="px-5 py-4">Supplier</th>
                            <th class="px-5 py-4">Layers</th>
                            <th class="px-5 py-4">Updated</th>
                            <th class="px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-stone-950/35">
                        @foreach ($layups as $layup)
                            <tr>
                                <td class="px-5 py-5 font-semibold text-white">{{ $layup->name }}</td>
                                <td class="px-5 py-5 text-stone-300">{{ $layup->supplier->name }}</td>
                                <td class="px-5 py-5 text-stone-300">{{ $layup->layers_count }}</td>
                                <td class="px-5 py-5 text-stone-400">{{ $layup->updated_at?->format('M d, Y') }}</td>
                                <td class="px-5 py-5">
                                    <div class="flex justify-end">
                                        <a href="{{ route('suppliers.layups.show', [$layup->supplier, $layup]) }}" class="btn-primary">Open</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $layups->links() }}</div>
        </section>
    </div>
</x-app-layout>
