<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Http\Requests\ImportSupplierRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierExportService;
use App\Services\SupplierImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct(
        private readonly SupplierRepositoryInterface $suppliers,
        private readonly SupplierExportService $exportService,
        private readonly SupplierImportService $importService,
    ) {
    }

    public function index(): View
    {
        $search = request('search');

        return view('suppliers.index', [
            'suppliers' => $this->suppliers->paginateWithHierarchy(10, $search)->withQueryString(),
            'summary' => $this->suppliers->summary(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $supplier = $this->suppliers->create($request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier): View
    {
        return view('suppliers.show', [
            'supplier' => $this->suppliers->loadHierarchy($supplier),
            'conflictReport' => session('conflict_report'),
            'importSummary' => session('import_summary'),
        ]);
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->suppliers->update($supplier, $request->validated());

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->suppliers->delete($supplier);

        return redirect()
            ->route('suppliers.index')
            ->with('status', 'Supplier deleted successfully.');
    }

    public function export(Supplier $supplier)
    {
        $payload = $this->exportService->export($supplier);
        $filename = Str::slug($supplier->name).'-export.json';

        return response()->streamDownload(
            fn () => print json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            $filename,
            ['Content-Type' => 'application/json']
        );
    }

    public function import(ImportSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $contents = $request->hasFile('json_file')
            ? $request->file('json_file')->get()
            : (string) $request->string('payload');

        $payload = $this->importService->parse($contents);
        $analysis = $this->importService->analyze($supplier, $payload);
        $strategy = $request->validated('strategy');

        if ($strategy === 'manual' && $analysis['conflicts'] !== []) {
            $token = (string) Str::uuid();

            Session::put($this->conflictSessionKey($token), [
                'supplier_id' => $supplier->id,
                'payload' => $payload,
                'conflicts' => $analysis['conflicts'],
                'decisions' => [],
            ]);

            return redirect()->route('suppliers.import-conflicts.show', [
                'supplier' => $supplier,
                'token' => $token,
                'index' => 0,
            ]);
        }

        $result = $this->importService->import($supplier, $payload, $strategy);

        if ($result['status'] === 'rejected') {
            return redirect()
                ->route('suppliers.show', $supplier)
                ->with('conflict_report', $result['conflicts']);
        }

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('import_summary', $result)
            ->with('status', 'Import completed successfully.');
    }

    public function conflictSessionKey(string $token): string
    {
        return 'supplier-import-conflicts:'.$token;
    }
}
