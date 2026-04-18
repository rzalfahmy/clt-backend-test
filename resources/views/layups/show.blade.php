<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Layup Workspace</p>
                <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">{{ $layup->name }}</h1>
                <p class="mt-3 text-sm text-stone-300">{{ $supplier->name }} • {{ $layup->layers->count() }} layers • total thickness {{ number_format($totalThickness, 2) }} mm.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('suppliers.layups.layers.create', [$supplier, $layup]) }}" class="btn-primary">Add Layer</a>
                <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}" class="btn-secondary">Edit Layup</a>
                <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary">Back to Supplier</a>
            </div>
        </div>
    </x-slot>

    <div class="shell space-y-8">
        @include('partials.flash')

        <section class="grid gap-4 md:grid-cols-4">
            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Layup ID</p>
                <p class="mt-3 font-['Space_Grotesk'] text-2xl font-bold text-white">L-{{ str_pad((string) $layup->id, 3, '0', STR_PAD_LEFT) }}</p>
            </article>
            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Created By</p>
                <p class="mt-3 font-['Space_Grotesk'] text-2xl font-bold text-white">{{ $supplier->name }}</p>
            </article>
            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Total Thickness</p>
                <p class="mt-3 font-['Space_Grotesk'] text-2xl font-bold text-emerald-200">{{ number_format($totalThickness, 2) }} mm</p>
            </article>
            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Total Layers</p>
                <p class="mt-3 font-['Space_Grotesk'] text-2xl font-bold text-white">{{ $layup->layers->count() }}</p>
            </article>
        </section>

        <section class="grid gap-8 xl:grid-cols-[1fr_0.95fr]">
            <div class="space-y-6">
                <section class="panel p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-['Space_Grotesk'] text-2xl font-bold text-white">Layer Composition</h2>
                            <p class="mt-2 text-sm text-stone-400">Manage ordered layer data for this layup.</p>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto rounded-3xl border border-white/10">
                        <table class="min-w-[755px] w-full divide-y divide-white/10 text-sm">
                            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.16em] text-stone-400">
                                <tr>
                                    <th class="px-5 py-4">Order</th>
                                    <th class="px-5 py-4">Thickness</th>
                                    <th class="px-5 py-4">Width</th>
                                    <th class="px-5 py-4">Angle</th>
                                    <th class="px-5 py-4">Orientation</th>
                                    <th class="px-5 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10 bg-stone-950/35">
                                @forelse ($layup->layers as $layer)
                                    <tr>
                                        <td class="px-5 py-5 font-semibold text-white">{{ $layer->layer_order }}</td>
                                        <td class="px-5 py-5 text-stone-300">{{ number_format($layer->thickness, 2) }} mm</td>
                                        <td class="px-5 py-5 text-stone-300">{{ number_format($layer->width, 2) }} mm</td>
                                        <td class="px-5 py-5 text-stone-300">{{ number_format($layer->angle, 0) }}&deg;</td>
                                        <td class="px-5 py-5">
                                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ (int) $layer->angle === 90 ? 'border-amber-400/30 bg-amber-500/10 text-amber-200' : 'border-emerald-400/30 bg-emerald-500/10 text-emerald-200' }}">
                                                {{ (int) $layer->angle === 90 ? 'Transverse' : 'Longitudinal' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('suppliers.layups.layers.edit', [$supplier, $layup, $layer]) }}" class="btn-secondary">Edit</a>
                                                <form method="POST" action="{{ route('suppliers.layups.layers.destroy', [$supplier, $layup, $layer]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger" onclick="return confirm('Delete this layer?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center text-stone-400">No layers yet. Add the first layer to this layup.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="panel-soft p-5">
                    <h3 class="font-['Space_Grotesk'] text-lg font-semibold text-white">Engineering Note</h3>
                    <p class="mt-3 text-sm leading-6 text-stone-400">Keep layer order sequential and review 90 degree layers carefully during import. Conflict resolution will compare any imported layer against the same layup name and layer order.</p>
                </section>
            </div>

            <section class="panel p-6 sm:p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-['Space_Grotesk'] text-2xl font-bold text-white">Structure Visualizer</h2>
                        <p class="mt-2 text-sm text-stone-400">A quick visual stack of the layup from top to bottom.</p>
                    </div>
                    <div class="flex gap-3 text-xs text-stone-400">
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-sm bg-emerald-200/70"></span>Longitudinal</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-sm bg-amber-200/70"></span>Transverse</span>
                    </div>
                </div>

                <div class="mt-8 rounded-[2rem] border border-white/10 bg-white/5 px-6 py-10">
                    <div class="mx-auto flex max-w-md flex-col gap-2">
                        @forelse ($layup->layers as $layer)
                            <div class="rounded-md border px-4 py-5 text-center text-sm font-semibold {{ (int) $layer->angle === 90 ? 'border-amber-300/50 bg-amber-200/75 text-stone-900' : 'border-emerald-300/50 bg-emerald-100/85 text-stone-900' }}">
                                L{{ $layer->layer_order }} • {{ number_format($layer->thickness, 2) }} mm • {{ number_format($layer->angle, 0) }}°
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-white/15 px-4 py-12 text-center text-stone-400">
                                Visualizer will appear after layers are added.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </section>
    </div>
</x-app-layout>
