<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Contracts\Repositories\LayupRepositoryInterface;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        private readonly LayupRepositoryInterface $layups,
        private readonly LayerRepositoryInterface $layers,
    ) {
    }

    public function layups(): View
    {
        $search = request('search');

        return view('catalog.layups', [
            'layups' => $this->layups->paginateWithRelations(12, $search)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function layers(): View
    {
        $search = request('search');

        return view('catalog.layers', [
            'layers' => $this->layers->paginateWithRelations(20, $search)->withQueryString(),
            'search' => $search,
        ]);
    }
}
