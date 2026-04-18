<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">CLT Assignment</p>
                <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">Suppliers</h1>
                <p class="mt-3 max-w-2xl text-sm text-stone-300">Manage timber suppliers, nested layups, supplier exports, and import conflict strategies from one place.</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn-primary">Add Supplier</a>
        </div>
    </x-slot>

    <div class="shell space-y-8">
        @include('partials.flash')

        <section class="grid gap-4 md:grid-cols-3">
            <article class="stat-card">
                <p class="text-sm text-stone-400">Suppliers</p>
                <p class="mt-3 font-['Space_Grotesk'] text-4xl font-bold text-white">{{ $summary['suppliers'] }}</p>
            </article>
            <article class="stat-card">
                <p class="text-sm text-stone-400">Layups</p>
                <p class="mt-3 font-['Space_Grotesk'] text-4xl font-bold text-white">{{ $summary['layups'] }}</p>
            </article>
            <article class="stat-card">
                <p class="text-sm text-stone-400">Layers</p>
                <p class="mt-3 font-['Space_Grotesk'] text-4xl font-bold text-white">{{ $summary['layers'] }}</p>
            </article>
        </section>

        <section class="panel p-6 sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="font-['Space_Grotesk'] text-2xl font-bold text-white">Supplier Directory</h2>
                    <p class="mt-2 text-sm text-stone-400">Open a supplier to manage its layups, layers, export package, and import flow.</p>
                </div>

                <form method="GET" action="{{ route('suppliers.index') }}" class="flex w-full max-w-xl flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ $search }}" class="field mt-0" placeholder="Search suppliers by name...">
                    <button type="submit" class="btn-secondary">Search</button>
                    @if($search)
                        <a href="{{ route('suppliers.index') }}" class="btn-secondary">Reset</a>
                    @endif
                </form>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10">
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.2em] text-stone-400">
                        <tr>
                            <th class="px-5 py-4">Name</th>
                            <th class="px-5 py-4">Total Layups</th>
                            <th class="px-5 py-4">Total Layers</th>
                            <th class="px-5 py-4">Created At</th>
                            <th class="px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-stone-950/35 text-sm">
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full border border-emerald-400/20 bg-emerald-500/10 font-semibold text-emerald-200">
                                            {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $supplier->name }}</p>
                                            <p class="mt-1 text-xs text-stone-500">ID: SUP-{{ str_pad((string) $supplier->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 text-stone-300">{{ $supplier->layups_count }}</td>
                                <td class="px-5 py-5 text-stone-300">{{ $supplier->layers_count }}</td>
                                <td class="px-5 py-5 text-stone-400">{{ $supplier->created_at?->format('M d, Y') }}</td>
                                <td class="px-5 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn-primary">Open</a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Delete this supplier and all nested records?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-stone-400">
                                    No suppliers yet. Create one to start building CLT layups.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $suppliers->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
