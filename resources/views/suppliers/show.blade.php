<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">
                    Supplier Workspace
                </p>

                <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">
                    {{ $supplier->name }}
                </h1>

                <p class="mt-3 text-sm text-stone-300">
                    {{ $supplier->layups->count() }} layups and
                    {{ $supplier->layups->sum(fn ($layup) => $layup->layers->count()) }} layers ready for export.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 justify-end">
                <a href="{{ route('suppliers.export', $supplier) }}" class="btn-primary">
                    Export JSON
                </a>

                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-secondary">
                    Edit Supplier
                </a>

                <a href="{{ route('suppliers.index') }}" class="btn-secondary">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="shell space-y-8">

        @include('partials.flash')

        <!-- STATS -->
        <section class="grid gap-4 md:grid-cols-4">

            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Supplier ID</p>
                <p class="mt-3 text-2xl font-bold text-white">
                    SUP-{{ str_pad($supplier->id, 4, '0', STR_PAD_LEFT) }}
                </p>
            </article>

            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Layups</p>
                <p class="mt-3 text-2xl font-bold text-white">
                    {{ $supplier->layups->count() }}
                </p>
            </article>

            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Layers</p>
                <p class="mt-3 text-2xl font-bold text-white">
                    {{ $supplier->layups->sum(fn ($l) => $l->layers->count()) }}
                </p>
            </article>

            <article class="stat-card">
                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Created</p>
                <p class="mt-3 text-2xl font-bold text-white">
                    {{ $supplier->created_at?->format('M d, Y') }}
                </p>
            </article>

        </section>

        <!-- MAIN GRID -->
        <section class="grid gap-8 xl:grid-cols-3">

            <!-- LEFT -->
            <div class="panel p-6 sm:p-8 xl:col-span-2">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Layups</h2>
                        <p class="mt-1 text-sm text-stone-400">
                            Manage layups and layers
                        </p>
                    </div>

                    <a href="{{ route('suppliers.layups.create', $supplier) }}" class="btn-primary">
                        + Add Layup
                    </a>
                </div>

                <div class="mt-6 overflow-auto max-h-[420px] rounded-3xl border border-white/10">
                    <table class="min-w-[900px] w-full divide-y divide-white/10 text-sm leading-relaxed">

                        <thead class="bg-white/5 text-xs uppercase text-stone-400 sticky top-0 z-10 backdrop-blur">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Thickness</th>
                                <th class="px-6 py-4">Layers</th>
                                <th class="px-6 py-4">Updated</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/10 bg-stone-950/40">

                        @forelse ($supplier->layups as $layup)
                            <tr>

                                <td class="px-6 py-6 text-stone-400">
                                    L-{{ str_pad($layup->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-6 py-6">
                                    <p class="font-semibold text-white">{{ $layup->name }}</p>
                                    <p class="text-xs text-stone-500">
                                        {{ $layup->layers->count() }} layers
                                    </p>
                                </td>

                                <td class="px-6 py-6 text-stone-300">
                                    {{ number_format($layup->layers->sum('thickness'), 2) }} mm
                                </td>

                                <td class="px-6 py-6 text-stone-300">
                                    {{ $layup->layers->count() }}
                                </td>

                                <td class="px-6 py-6 text-stone-400">
                                    {{ $layup->updated_at?->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-6">
                                    <span class="px-3 py-1 text-xs rounded-full border
                                        {{ $layup->layers->count()
                                            ? 'bg-emerald-500/10 text-emerald-300 border-emerald-400/30'
                                            : 'bg-amber-500/10 text-amber-300 border-amber-400/30' }}">
                                        {{ $layup->layers->count() ? 'Active' : 'Draft' }}
                                    </span>
                                </td>

                                <td class="px-6 py-6 text-right">
                                    <div class="flex justify-end gap-2">

                                        <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                                           class="btn-primary">
                                            Open
                                        </a>

                                        <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}"
                                           class="btn-secondary opacity-80 hover:opacity-100">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('suppliers.layups.destroy', [$supplier, $layup]) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn-danger opacity-80 hover:opacity-100">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-stone-400">
                                    No layups yet
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

            <!-- RIGHT -->
            <aside class="panel p-6 sm:p-8 space-y-6">

                <div>
                    <h2 class="text-2xl font-bold text-white">Import JSON</h2>
                    <p class="text-sm text-stone-400 mt-1">
                        Upload file or paste JSON
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('suppliers.import', $supplier) }}"
                      enctype="multipart/form-data"
                      class="space-y-5">

                    @csrf

                    <!-- STRATEGY -->
                    <div>
                        <label class="label">Strategy</label>
                        <select name="strategy" class="field bg-stone-900 border-white/20">
                            <option value="overwrite">Overwrite</option>
                            <option value="skip">Skip</option>
                            <option value="reject">Reject</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>

                    <!-- FILE -->
                    <div>
                        <label class="label">Upload JSON</label>
                        <input type="file" name="json_file"
                            class="mt-2 w-full text-sm text-stone-300 file:mr-4 file:rounded-lg file:border-0 file:bg-amber-400 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-stone-900 hover:file:bg-amber-300"
                        >
                    </div>

                    <!-- TEXTAREA -->
                    <div>
                        <label class="label">Paste JSON</label>
                        <textarea name="payload"
                            rows="8"
                            class="field font-mono text-xs resize-none"
                            placeholder="Paste JSON here..."></textarea>
                    </div>

                    <button class="btn-primary w-full">
                        Run Import
                    </button>

                </form>

            </aside>

        </section>

    </div>

</x-app-layout>
