<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function paginateWithHierarchy(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return Supplier::query()
            ->when($search, fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
            ->withCount('layups')
            ->withCount([
                'layups as layers_count' => fn ($query) => $query->join('layers', 'layers.layup_id', '=', 'layups.id'),
            ])
            ->latest()
            ->paginate($perPage);
    }

    public function loadHierarchy(Supplier $supplier): Supplier
    {
        return $supplier->load([
            'layups' => fn ($query) => $query->orderBy('name'),
            'layups.layers' => fn ($query) => $query->orderBy('layer_order'),
        ]);
    }

    public function create(array $attributes): Supplier
    {
        return Supplier::create($attributes);
    }

    public function update(Supplier $supplier, array $attributes): Supplier
    {
        $supplier->update($attributes);

        return $supplier->refresh();
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }

    public function summary(): array
    {
        return [
            'suppliers' => Supplier::count(),
            'layups' => Layup::count(),
            'layers' => Layer::count(),
        ];
    }
}
