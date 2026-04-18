<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Http\Requests\StoreLayerRequest;
use App\Http\Requests\UpdateLayerRequest;
use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayerController extends Controller
{
    public function __construct(
        private readonly LayerRepositoryInterface $layers,
    ) {
    }

    public function create(Supplier $supplier, Layup $layup): View
    {
        return view('layers.create', compact('supplier', 'layup'));
    }

    public function store(StoreLayerRequest $request, Supplier $supplier, Layup $layup): RedirectResponse
    {
        $this->layers->create($layup, $request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Layer created successfully.');
    }

    public function edit(Supplier $supplier, Layup $layup, Layer $layer): View
    {
        return view('layers.edit', compact('supplier', 'layup', 'layer'));
    }

    public function update(UpdateLayerRequest $request, Supplier $supplier, Layup $layup, Layer $layer): RedirectResponse
    {
        $this->layers->update($layer, $request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Layer updated successfully.');
    }

    public function destroy(Supplier $supplier, Layup $layup, Layer $layer): RedirectResponse
    {
        $this->layers->delete($layer);

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Layer deleted successfully.');
    }
}
