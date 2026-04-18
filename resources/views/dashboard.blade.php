<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-xs uppercase tracking-[0.3em] text-amber-300">
                CLT Workspace
            </p>
            <h2 class="font-['Space_Grotesk'] text-3xl font-bold text-white">
                Dashboard
            </h2>
            <p class="text-sm text-stone-400">
                Overview of your suppliers, layups, and layers.
            </p>
        </div>
    </x-slot>

    <div class="shell space-y-8">

        <!-- STATS -->
        <div class="grid gap-4 md:grid-cols-3">

            <div class="stat-card">
                <p class="text-xs uppercase tracking-wide text-stone-400">
                    Suppliers
                </p>
                <p class="mt-2 text-3xl font-bold text-white">
                    {{ $suppliers ?? 0 }}
                </p>
            </div>

            <div class="stat-card">
                <p class="text-xs uppercase tracking-wide text-stone-400">
                    Layups
                </p>
                <p class="mt-2 text-3xl font-bold text-white">
                    {{ $layups ?? 0 }}
                </p>
            </div>

            <div class="stat-card">
                <p class="text-xs uppercase tracking-wide text-stone-400">
                    Layers
                </p>
                <p class="mt-2 text-3xl font-bold text-white">
                    {{ $layers ?? 0 }}
                </p>
            </div>

        </div>

        <!-- WELCOME -->
        <div class="panel p-6 sm:p-8">
            <h3 class="text-lg font-semibold text-white">
                You're logged in 👋
            </h3>

            <p class="mt-2 text-sm text-stone-400">
                Welcome back! Use the quick actions below to manage your CLT data efficiently.
            </p>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="grid gap-6 md:grid-cols-3">

            <div class="panel p-6 hover:bg-white/5 transition">
                <h4 class="text-xs uppercase tracking-wide text-stone-400">
                    Suppliers
                </h4>

                <p class="mt-2 text-white text-lg font-semibold">
                    Manage suppliers
                </p>

                <p class="text-xs text-stone-500 mt-1">
                    Create, edit, and organize supplier data
                </p>

                <a href="{{ route('suppliers.index') }}" class="btn-primary mt-4">
                    Open
                </a>
            </div>

            <div class="panel p-6 hover:bg-white/5 transition">
                <h4 class="text-xs uppercase tracking-wide text-stone-400">
                    Layups
                </h4>

                <p class="mt-2 text-white text-lg font-semibold">
                    Browse layups
                </p>

                <p class="text-xs text-stone-500 mt-1">
                    View CLT layup structures
                </p>

                <a href="{{ route('layups.index') }}" class="btn-secondary mt-4">
                    Open
                </a>
            </div>

            <div class="panel p-6 hover:bg-white/5 transition">
                <h4 class="text-xs uppercase tracking-wide text-stone-400">
                    Layers
                </h4>

                <p class="mt-2 text-white text-lg font-semibold">
                    Inspect layers
                </p>

                <p class="text-xs text-stone-500 mt-1">
                    Analyze layer specifications
                </p>

                <a href="{{ route('layers.index') }}" class="btn-secondary mt-4">
                    Open
                </a>
            </div>

        </div>

    </div>

</x-app-layout>
