<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Manual Conflict Resolution</p>
                <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">{{ $supplier->name }}</h1>
                <p class="mt-3 text-sm text-stone-300">Resolve discrepancy {{ $index + 1 }} of {{ $total }} before the import is applied.</p>
            </div>
            <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary">Exit Workspace</a>
        </div>
    </x-slot>

    <div class="shell space-y-8">
        @include('partials.flash')

        <section class="panel overflow-hidden p-0">
            <div class="grid min-h-[720px] lg:grid-cols-[280px_1fr]">
                <aside class="border-r border-white/10 bg-white/5 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Conflicting Layups</p>
                    <div class="mt-5 space-y-3">
                        @for ($i = 0; $i < $total; $i++)
                            <a href="{{ route('suppliers.import-conflicts.show', [$supplier, $token, $i]) }}" class="block rounded-2xl border px-4 py-4 {{ $i === $index ? 'border-emerald-400/50 bg-emerald-500/10' : 'border-white/10 bg-white/5' }}">
                                <p class="font-semibold text-white">{{ $i + 1 }}. {{ $i === $index ? $conflict['layup_name'] : 'Conflict '.$i + 1 }}</p>
                                <p class="mt-1 text-xs text-stone-400">{{ $i === $index ? 'Layer '.$conflict['layer_order'] : 'Review discrepancy' }}</p>
                            </a>
                        @endfor
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary w-full">Cancel Import</a>
                    </div>
                </aside>

                <div class="p-6 sm:p-8">
                    <div class="flex flex-col gap-2">
                        <h2 class="font-['Space_Grotesk'] text-2xl font-bold text-white">{{ $conflict['layup_name'] }} • Layer {{ $conflict['layer_order'] }}</h2>
                        <p class="text-sm text-stone-400">Highlighted fields: {{ implode(', ', $conflict['diff_fields']) }}</p>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-2">
                        <article class="panel-soft p-5">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-stone-400">Existing Version</p>
                            <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
                                <table class="min-w-full divide-y divide-white/10 text-sm">
                                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.16em] text-stone-400">
                                        <tr>
                                            <th class="px-4 py-3">Field</th>
                                            <th class="px-4 py-3">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/10">
                                        @foreach ($conflict['existing'] as $field => $value)
                                            <tr class="{{ in_array($field, $conflict['diff_fields'], true) ? 'bg-rose-500/10' : '' }}">
                                                <td class="px-4 py-3 text-stone-400">{{ ucfirst($field) }}</td>
                                                <td class="px-4 py-3 font-semibold text-white">{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <form method="POST" action="{{ route('suppliers.import-conflicts.resolve', [$supplier, $token, $index]) }}" class="mt-5">
                                @csrf
                                <input type="hidden" name="decision" value="keep_existing">
                                <button type="submit" class="btn-secondary w-full">Keep Existing</button>
                            </form>
                        </article>

                        <article class="panel-soft p-5">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-amber-300">Incoming Version</p>
                            <div class="mt-4 overflow-hidden rounded-2xl border border-white/10">
                                <table class="min-w-full divide-y divide-white/10 text-sm">
                                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.16em] text-stone-400">
                                        <tr>
                                            <th class="px-4 py-3">Field</th>
                                            <th class="px-4 py-3">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/10">
                                        @foreach ($conflict['incoming'] as $field => $value)
                                            <tr class="{{ in_array($field, $conflict['diff_fields'], true) ? 'bg-amber-500/10' : '' }}">
                                                <td class="px-4 py-3 text-stone-400">{{ ucfirst($field) }}</td>
                                                <td class="px-4 py-3 font-semibold text-white">{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <form method="POST" action="{{ route('suppliers.import-conflicts.resolve', [$supplier, $token, $index]) }}" class="mt-5">
                                @csrf
                                <input type="hidden" name="decision" value="accept_incoming">
                                <button type="submit" class="btn-primary w-full">Accept Incoming</button>
                            </form>
                        </article>
                    </div>

                    <div class="mt-8 flex items-center justify-between text-sm text-stone-400">
                        <div>
                            @if ($index > 0)
                                <a href="{{ route('suppliers.import-conflicts.show', [$supplier, $token, $index - 1]) }}" class="btn-secondary">Previous Conflict</a>
                            @endif
                        </div>
                        <p>{{ $index + 1 }} of {{ $total }} discrepancies</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
