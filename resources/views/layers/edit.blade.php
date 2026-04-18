<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="font-['Space_Grotesk'] text-xs font-bold uppercase tracking-[0.35em] text-amber-300">Edit Layer</p>
            <h1 class="mt-3 font-['Space_Grotesk'] text-3xl font-bold text-white">{{ $layup->name }} • Layer {{ $layer->layer_order }}</h1>
        </div>
    </x-slot>

    <div class="shell">
        <div class="panel max-w-3xl p-6 sm:p-8">
            @include('partials.flash')

            <form method="POST" action="{{ route('suppliers.layups.layers.update', [$supplier, $layup, $layer]) }}" class="grid gap-6 md:grid-cols-2">
                @csrf
                @method('PUT')
                <div>
                    <label for="layer_order" class="label">Layer order</label>
                    <input id="layer_order" name="layer_order" value="{{ old('layer_order', $layer->layer_order) }}" class="field" type="number" min="1" required>
                </div>
                <div>
                    <label for="thickness" class="label">Thickness</label>
                    <input id="thickness" name="thickness" value="{{ old('thickness', $layer->thickness) }}" class="field" type="number" step="0.01" min="0.01" required>
                </div>
                <div>
                    <label for="width" class="label">Width</label>
                    <input id="width" name="width" value="{{ old('width', $layer->width) }}" class="field" type="number" step="0.01" min="0.01" required>
                </div>
                <div>
                    <label for="angle" class="label">Angle</label>
                    <input id="angle" name="angle" value="{{ old('angle', $layer->angle) }}" class="field" type="number" step="0.01" min="-360" max="360" required>
                </div>

                <div class="md:col-span-2 flex gap-3">
                    <button type="submit" class="btn-primary">Save Changes</button>
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
