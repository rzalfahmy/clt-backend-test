<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResolveImportConflictRequest;
use App\Models\Supplier;
use App\Services\SupplierImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class SupplierImportConflictController extends Controller
{
    public function __construct(
        private readonly SupplierImportService $importService,
    ) {
    }

    public function show(Supplier $supplier, string $token, int $index = 0): View|RedirectResponse
    {
        $session = Session::get($this->sessionKey($token));

        if (! $session || $session['supplier_id'] !== $supplier->id) {
            return redirect()
                ->route('suppliers.show', $supplier)
                ->withErrors(['payload' => 'The conflict resolution session is no longer available.']);
        }

        $conflicts = $session['conflicts'];
        $index = max(0, min($index, count($conflicts) - 1));

        return view('imports.conflicts', [
            'supplier' => $supplier,
            'token' => $token,
            'conflict' => $conflicts[$index],
            'index' => $index,
            'total' => count($conflicts),
            'decisions' => $session['decisions'],
        ]);
    }

    public function resolve(
        ResolveImportConflictRequest $request,
        Supplier $supplier,
        string $token,
        int $index,
    ): RedirectResponse {
        $session = Session::get($this->sessionKey($token));

        if (! $session || $session['supplier_id'] !== $supplier->id) {
            return redirect()
                ->route('suppliers.show', $supplier)
                ->withErrors(['payload' => 'The conflict resolution session is no longer available.']);
        }

        $conflict = $session['conflicts'][$index] ?? null;

        if (! $conflict) {
            return redirect()->route('suppliers.show', $supplier);
        }

        $session['decisions'][$conflict['key']] = $request->validated('decision');
        Session::put($this->sessionKey($token), $session);

        if (($index + 1) < count($session['conflicts'])) {
            return redirect()->route('suppliers.import-conflicts.show', [
                'supplier' => $supplier,
                'token' => $token,
                'index' => $index + 1,
            ]);
        }

        $result = $this->importService->import(
            $supplier,
            $session['payload'],
            'manual',
            $session['decisions'],
        );

        Session::forget($this->sessionKey($token));

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('import_summary', $result)
            ->with('status', 'Import completed after resolving conflicts.');
    }

    private function sessionKey(string $token): string
    {
        return 'supplier-import-conflicts:'.$token;
    }
}
