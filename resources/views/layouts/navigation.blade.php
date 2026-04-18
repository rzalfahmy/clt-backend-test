<nav x-data="{ open: false }" class="relative z-50 border-b border-white/10 bg-stone-950/80 backdrop-blur">
    <div class="shell">
        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex">

                <!-- LOGO -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-400 text-sm font-bold text-stone-950">
                                CLT
                            </div>
                            <div>
                                <p class="font-['Space_Grotesk'] text-sm font-bold uppercase tracking-[0.3em] text-amber-300">
                                    Spec Toolbox
                                </p>
                                <p class="text-xs text-stone-400">
                                    Supplier layup workspace
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- MENU -->
                <div class="hidden space-x-8 sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Overview
                    </x-nav-link>

                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                        Suppliers
                    </x-nav-link>

                    <x-nav-link :href="route('layups.index')" :active="request()->routeIs('layups.index')">
                        Layups
                    </x-nav-link>

                    <x-nav-link :href="route('layers.index')" :active="request()->routeIs('layers.index')">
                        Layers
                    </x-nav-link>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <x-dropdown align="right" width="48">

                    <!-- BUTTON -->
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="w-4 h-4 opacity-70" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                    </x-slot>

                    <!-- DROPDOWN -->
                    <x-slot name="content">
                        <div class="py-2 bg-stone-900/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl">

                            <x-dropdown-link
                                :href="route('profile.edit')"
                                class="block px-4 py-2 text-sm text-stone-200 hover:bg-white/10 rounded-lg transition"
                            >
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-300 hover:bg-red-500/10 rounded-lg transition"
                                >
                                    Log Out
                                </button>
                            </form>

                        </div>
                    </x-slot>

                </x-dropdown>

            </div>

            <!-- MOBILE -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center rounded-xl p-2 text-stone-300 hover:bg-white/10">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                        <path :class="{'hidden': !open, 'inline-flex': open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>
