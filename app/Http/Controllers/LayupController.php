<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Http\Requests\StoreLayupRequest;
use App\Http\Requests\UpdateLayupRequest;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayupController extends Controller
{
    public function __construct(
        private readonly LayupRepositoryInterface $layups,
    ) {
    }

    public function show(Supplier $supplier, Layup $layup): View
    {
        $layup = $this->layups->loadLayers($layup);
        $totalThickness = $layup->layers->sum('thickness');

        return view('layups.show', [
            'supplier' => $supplier,
            'layup' => $layup,
            'totalThickness' => $totalThickness,
        ]);
    }

    public function create(Supplier $supplier): View
    {
        return view('layups.create', compact('supplier'));
    }

    public function store(StoreLayupRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->layups->create($supplier, $request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Layup created successfully.');
    }

    public function edit(Supplier $supplier, Layup $layup): View
    {
        return view('layups.edit', compact('supplier', 'layup'));
    }

    public function update(UpdateLayupRequest $request, Supplier $supplier, Layup $layup): RedirectResponse
    {
        $this->layups->update($layup, $request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Layup updated successfully.');
    }

    public function destroy(Supplier $supplier, Layup $layup): RedirectResponse
    {
        $this->layups->delete($layup);

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Layup deleted successfully.');
    }
}
