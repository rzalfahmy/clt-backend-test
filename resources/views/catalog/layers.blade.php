<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Catalog</p>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">Layers</h1>
        </div>
    </x-slot>

    <div class="shell space-y-8">
        <section class="panel p-6 sm:p-8">
            <form method="GET" action="{{ route('layers.index') }}" class="flex w-full max-w-xl flex-col gap-3 sm:flex-row">
                <input type="text" name="search" value="{{ $search }}" class="field mt-0" placeholder="Search by layup or supplier...">
                <button type="submit" class="btn-secondary">Search</button>
            </form>

            <div class="mt-6 overflow-hidden rounded-3xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.16em] text-stone-400">
                        <tr>
                            <th class="px-5 py-4">Supplier</th>
                            <th class="px-5 py-4">Layup</th>
                            <th class="px-5 py-4">Order</th>
                            <th class="px-5 py-4">Thickness</th>
                            <th class="px-5 py-4">Width</th>
                            <th class="px-5 py-4">Angle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-stone-950/35">
                        @foreach ($layers as $layer)
                            <tr>
                                <td class="px-5 py-5 text-stone-300">{{ $layer->layup->supplier->name }}</td>
                                <td class="px-5 py-5 text-white">{{ $layer->layup->name }}</td>
                                <td class="px-5 py-5 text-stone-300">{{ $layer->layer_order }}</td>
                                <td class="px-5 py-5 text-stone-300">{{ number_format($layer->thickness, 2) }} mm</td>
                                <td class="px-5 py-5 text-stone-300">{{ number_format($layer->width, 2) }} mm</td>
                                <td class="px-5 py-5 text-stone-300">{{ number_format($layer->angle, 0) }}°</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $layers->links() }}</div>
        </section>
    </div>
</x-app-layout>
