<?php

namespace App\Services;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;

class SupplierExportService
{
    public function __construct(
        private readonly SupplierRepositoryInterface $suppliers,
    ) {
    }

    public function export(Supplier $supplier): array
    {
        $supplier = $this->suppliers->loadHierarchy($supplier);

        return [
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
            ],
            'layups' => $supplier->layups->map(fn ($layup) => [
                'id' => $layup->id,
                'name' => $layup->name,
                'layers' => $layup->layers->map(fn ($layer) => [
                    'id' => $layer->id,
                    'layer_order' => $layer->layer_order,
                    'thickness' => (float) $layer->thickness,
                    'width' => (float) $layer->width,
                    'angle' => (float) $layer->angle,
                ])->values()->all(),
            ])->values()->all(),
        ];
    }
}
